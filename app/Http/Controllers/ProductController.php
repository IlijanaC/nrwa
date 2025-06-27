<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate; // Potrebna 'use' izjava za Gate fasadu

class ProductController extends Controller
{
    // Prikaz svih proizvoda
    public function index()
    {
        // Provjera autorizacije obično se radi u Blade predlošku ili na ruti,
        // ali ako je želite ovdje:
        // if (Auth::check() && !Auth::user()->can('view products')) {
        //     abort(403, 'Niste ovlašteni za pregled proizvoda.');
        // }

        $products = Product::with('productType')->get(); // Učitavanje 'productType' relacije
        return view('products.index', compact('products'));
    }

    // Prikaz forme za unos novog proizvoda
    public function create()
    {
        if (!Auth::user()->can('create products')) {
            abort(403, 'Niste ovlašteni za kreiranje proizvoda.');
        }

        $productTypes = ProductType::all();
        // Dohvati sve kupce za odabir u formi za kreiranje proizvoda
        $allCustomers = Customer::all();
        // Kada kreiramo novi proizvod, nema još povezanih kupaca
        $associatedCustomerIds = []; 

        return view('products.create', compact('productTypes', 'allCustomers', 'associatedCustomerIds'));
    }

    // Spremanje novog proizvoda
    public function store(Request $request)
    {
        if (!Auth::user()->can('create products')) {
            abort(403, 'Niste ovlašteni za spremanje proizvoda.');
        }

        // Ažurirana validacija:
        // - 'unique:product,PRODUCT_CD' koristi naziv tvoje tablice 'product'
        // - 'exists:product_type,PRODUCT_TYPE_CD' koristi naziv tvoje tablice 'product_type'
        // - Dodani su 'customers' i 'customers.*' za many-to-many vezu
        $validatedData = $request->validate([ // Ova linija mora definirati $validatedData
            'PRODUCT_CD' => 'required|string|unique:product,PRODUCT_CD|max:10', 
            'NAME' => 'required|string|max:50',
            'DATE_OFFERED' => 'nullable|date',
            'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
            'PRODUCT_TYPE_CD' => 'nullable|exists:product_type,PRODUCT_TYPE_CD', // <-- Zarez je ovdje bio dodan ranije
            'customers' => 'array', // Polje 'customers' mora biti niz
            'customers.*' => 'exists:customer,CUST_ID', // Svaki element niza mora postojati kao CUST_ID u tablici 'customer'
        ]);

        // KLJUČNA PROMJENA: Uhvati instancu stvorenog proizvoda u varijablu $product
        $product = Product::create([
            'PRODUCT_CD' => $validatedData['PRODUCT_CD'],
            'NAME' => $validatedData['NAME'],
            'DATE_OFFERED' => $validatedData['DATE_OFFERED'],
            'DATE_RETIRED' => $validatedData['DATE_RETIRED'],
            'PRODUCT_TYPE_CD' => $validatedData['PRODUCT_TYPE_CD'],
            // Ne uključujte 'customers' direktno u 'create' jer to nije polje u Product tablici
        ]);

        // Sinkronizirajte many-to-many relaciju s kupcima
        // Koristi se input('customers', []) kako bi se osiguralo da je niz prazan ako polje nije poslano
        $product->customers()->sync($request->input('customers', []));

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno kreiran.');
    }

    // Prikaz određenog proizvoda (opcionalno)
    public function show($id)
    {
        // Provjera dozvole
        if (Auth::check() && !Auth::user()->can('view products')) {
            abort(403, 'Niste ovlašteni za pregled ovog proizvoda.');
        }

        $product = Product::with(['productType', 'customers'])->findOrFail($id); // Učitavanje 'customers' relacije
        return view('products.show', compact('product'));
    }

    // Prikaz forme za uređivanje proizvoda
    public function edit($id)
    {
        // Provjera autorizacije
        if (!Auth::user()->can('edit products')) {
            abort(403, 'Niste ovlašteni za uređivanje ovog proizvoda.');
        }

        // Učitavanje 'customers' relacije za dobivanje povezanih kupaca
        $product = Product::with('customers')->findOrFail($id); 
        
        $productTypes = ProductType::all();
        $allCustomers = Customer::all(); // Dohvati SVE kupce za padajući izbornik
        
        // Dohvati ID-ove kupaca koji su VEĆ POVEZANI s ovim proizvodom
        // pluck('CUST_ID') jer je 'CUST_ID' primarni ključ Customer modela
        $associatedCustomerIds = $product->customers->pluck('CUST_ID')->toArray();

        // Proslijedi sve potrebne varijable u view
        return view('products.edit', compact('product', 'productTypes', 'allCustomers', 'associatedCustomerIds'));
    }

    // Ažuriranje proizvoda
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('edit products')) {
            abort(403, 'Niste ovlašteni za ažuriranje ovog proizvoda.');
        }

        // Ažurirana validacija:
        // - 'exists:product_type,PRODUCT_TYPE_CD' koristi naziv tvoje tablice 'product_type'
        // - Dodani su 'customers' i 'customers.*' za many-to-many vezu
        $validatedData = $request->validate([
            'NAME' => 'required|string|max:50',
            'DATE_OFFERED' => 'nullable|date',
            'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
            'PRODUCT_TYPE_CD' => 'nullable|exists:product_type,PRODUCT_TYPE_CD', // <-- Zarez je ovdje bio dodan ranije
            'customers' => 'array', // Polje 'customers' mora biti niz
            'customers.*' => 'exists:customer,CUST_ID', // Svaki element niza mora postojati kao CUST_ID u tablici 'customer'
        ]);

        $product = Product::findOrFail($id);
        
        // Ažuriraj samo dopuštena polja (ona koja su u $fillable u modelu)
        // Korištenje $validatedData osigurava da se ažuriraju samo validirana polja,
        // bez pokušaja ažuriranja npr. PRODUCT_CD koji je 'readonly'.
        $product->update([
            'NAME' => $validatedData['NAME'],
            'DATE_OFFERED' => $validatedData['DATE_OFFERED'],
            'DATE_RETIRED' => $validatedData['DATE_RETIRED'],
            'PRODUCT_TYPE_CD' => $validatedData['PRODUCT_TYPE_CD'],
        ]);

        // Sinkronizirajte many-to-many relaciju s kupcima
        // sync() će dodati/ukloniti unose u pivot tablici
        $product->customers()->sync($request->input('customers', []));

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno ažuriran.');
    }

    // Brisanje proizvoda
    public function destroy($id)
    {
        if (!Auth::user()->can('delete products')) {
            abort(403, 'Niste ovlašteni za brisanje ovog proizvoda.');
        }
        
        $product = Product::findOrFail($id);
        // Prije brisanja proizvoda, Laravel će automatski obrisati
        // povezane unose u pivot tablici zbog onDelete('cascade') definiranog u migraciji.
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno obrisan.');
    }
}
