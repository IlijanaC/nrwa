<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    // Prikaz svih kupaca
    public function index()
    {
        if (Auth::check() && !Auth::user()->can('view customers')) {
            abort(403, 'Niste ovlašteni za pregled kupaca.');
        }

        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    // Prikaz forme za unos novog kupca
    public function create()
    {
        if (!Auth::user()->can('create customers')) {
            abort(403, 'Niste ovlašteni za kreiranje kupaca.');
        }

        $allProducts = Product::all();
        // Kada kreiramo novog kupca, nema još povezanih proizvoda
        $associatedProductCds = [];

        return view('customers.create', compact('allProducts', 'associatedProductCds'));
    }

    // Spremanje novog kupca
    public function store(Request $request)
    {
        if (!Auth::user()->can('create customers')) {
            abort(403, 'Niste ovlašteni za spremanje kupaca.');
        }

        $validatedData = $request->validate([
            'ADDRESS' => 'required|max:255',
            'CITY' => 'required|max:100',
            'CUST_TYPE_CD' => 'required|max:10',
            'FED_ID' => 'nullable|max:50',
            'POSTAL_CODE' => 'nullable|max:20',
            'STATE' => 'nullable|max:50',
            'products' => 'array', // Polje 'products' mora biti niz
            'products.*' => 'exists:product,PRODUCT_CD',
        ]);

         $customer = Customer::create($validatedData);
        $customer->products()->sync($request->input('products', []));

        return redirect()->route('customers.index')->with('success', 'Kupac je uspješno kreiran.');
    }

    // Prikaz forme za uređivanje kupca
    public function edit($id)
    {
        if (!Auth::user()->can('edit customers')) {
            abort(403, 'Niste ovlašteni za uređivanje kupaca.');
        }

         $customer = Customer::with('products')->findOrFail($id);
        $products = Product::all();

          $associatedProductCds = $customer->products->pluck('PRODUCT_CD')->toArray();

        return view('customers.edit', compact('customer'));
    }

    // Ažuriranje kupca
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('edit customers')) {
            abort(403, 'Niste ovlašteni za ažuriranje kupaca.');
        }

        $request->validate([
            'ADDRESS' => 'required|max:255',
            'CITY' => 'required|max:100',
            'CUST_TYPE_CD' => 'required|max:10',
            'FED_ID' => 'nullable|max:50',
            'POSTAL_CODE' => 'nullable|max:20',
            'STATE' => 'nullable|max:50',
            'products' => 'array',              
            'products.*' => 'string|exists:product,PRODUCT_CD',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        $customer->products()->sync($request->input('products', []));

        return redirect()->route('customers.index')->with('success', 'Kupac je uspješno ažuriran.');
    }

    // Brisanje kupca
    public function destroy($id)
    {
        if (!Auth::user()->can('delete customers')) {
            abort(403, 'Niste ovlašteni za brisanje kupaca.');
        }

        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Kupac je uspješno obrisan.');
    }
}
