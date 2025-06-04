<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // Prikaz svih proizvoda
    public function index()
    {
          if (Auth::check() && !Auth::user()->can('view products')) {
            // Ova situacija se ne bi trebala dogoditi ako su dozvole ispravno postavljene
            // za viewer i editor uloge, jer će admin svakako proći.
            // Ako želite biti ultra-striktni da samo oni s 'view products' dozvolom mogu vidjeti:
            // abort(403, 'Niste ovlašteni za pregled proizvoda.');
        }

        $products = Product::with('productType')->get();
        return view('products.index', compact('products'));
    }

    // Prikaz forme za unos novog proizvoda
    public function create()
    {
         if (!Auth::user()->can('create products')) {
            abort(403, 'Niste ovlašteni za kreiranje proizvoda.');
        }

        $productTypes = ProductType::all();
        return view('products.create', compact('productTypes'));
    }

    // Spremanje novog proizvoda
    public function store(Request $request)
    {
         if (!Auth::user()->can('create products')) {
            abort(403, 'Niste ovlašteni za spremanje proizvoda.');
        }

        $request->validate([
            'PRODUCT_CD' => 'required|unique:PRODUCT,PRODUCT_CD|max:10',
            'NAME' => 'required|max:50',
            'DATE_OFFERED' => 'nullable|date',
            'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
            'PRODUCT_TYPE_CD' => 'nullable|exists:PRODUCT_TYPE,PRODUCT_TYPE_CD'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno kreiran.');
    }

    // Prikaz određenog proizvoda (opcionalno)
    public function show($id)
    {
        // Gosti smiju vidjeti.
        // Za prijavljene, provjera dozvole.
        if (Auth::check() && !Auth::user()->can('view products')) {
            // abort(403, 'Niste ovlašteni za pregled ovog proizvoda.');
        }

        $product = Product::with('productType')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Prikaz forme za uređivanje proizvoda
    public function edit($id)
    {
         if (!Auth::user()->can('edit products')) {
            abort(403, 'Niste ovlašteni za uređivanje ovog proizvoda.');
        }

        $product = Product::findOrFail($id);
        $productTypes = ProductType::all();
        return view('products.edit', compact('product', 'productTypes'));
    }

    // Ažuriranje proizvoda
    public function update(Request $request, $id)
    {
         if (!Auth::user()->can('edit products')) {
            abort(403, 'Niste ovlašteni za ažuriranje ovog proizvoda.');
        }

        $request->validate([
            'NAME' => 'required|max:50',
            'DATE_OFFERED' => 'nullable|date',
            'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
            'PRODUCT_TYPE_CD' => 'nullable|exists:PRODUCT_TYPE,PRODUCT_TYPE_CD'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno ažuriran.');
    }

    // Brisanje proizvoda
    public function destroy($id)
    {
        if (!Auth::user()->can('delete products')) {
            abort(403, 'Niste ovlašteni za brisanje ovog proizvoda.');
        }
        
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno obrisan.');
    }
}