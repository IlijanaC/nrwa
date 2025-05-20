<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;

class ProductController extends Controller
{
    // Prikaz svih proizvoda
    public function index()
    {
        $products = Product::with('productType')->get();
        return view('products.index', compact('products'));
    }

    // Prikaz forme za unos novog proizvoda
    public function create()
    {
        $productTypes = ProductType::all();
        return view('products.create', compact('productTypes'));
    }

    // Spremanje novog proizvoda
    public function store(Request $request)
    {
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
        $product = Product::with('productType')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    // Prikaz forme za uređivanje proizvoda
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $productTypes = ProductType::all();
        return view('products.edit', compact('product', 'productTypes'));
    }

    // Ažuriranje proizvoda
    public function update(Request $request, $id)
    {
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
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Proizvod je uspješno obrisan.');
    }
}