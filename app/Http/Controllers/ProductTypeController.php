<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;


class ProductTypeController extends Controller
{
    // Prikaz svih tipova proizvoda
    public function index()
    {
        $productTypes = ProductType::all();
        return view('product_types.index', compact('productTypes'));
    }

    // Forma za dodavanje novog tipa proizvoda
    public function create()
    {
        return view('product_types.create');
    }

    // Spremanje novog tipa proizvoda
    public function store(Request $request)
    {
        $request->validate([
            'PRODUCT_TYPE_CD' => 'required|unique:product_type,PRODUCT_TYPE_CD|max:255',
            'NAME' => 'nullable|max:50',
        ]);

        ProductType::create($request->all());

        return redirect()->route('product_types.index')
                         ->with('success', 'Product Type created successfully.');
    }

    // Forma za uređivanje postojećeg tipa proizvoda
    public function edit($product_type_cd)
    {
        $productType = ProductType::find($product_type_cd);
        
        // Ako proizvod nije pronađen, preusmjeri korisnika
        if (!$productType) {
            return redirect()->route('product_types.index')->withErrors('Product Type not found.');
        }

        return view('product_types.edit', compact('productType'));
    }

    // Ažuriranje postojećeg tipa proizvoda
    public function update(Request $request, $product_type_cd)
{
    {
        // Provjeriti postoji li proizvod s ovim PRODUCT_TYPE_CD
        $productType = ProductType::find($product_type_cd);

        if (!$productType) {
            return redirect()->route('product_types.index')->withErrors('Product Type not found.');
        }

        // Ažuriranje polja sa novim podacima
        $productType->name = $request->input('name');
        
        // Spremanje promjena
        $productType->save();

        return redirect()->route('product_types.index')->with('success', 'Product Type updated successfully.');
    }
}


    // Brisanje tipa proizvoda
    public function destroy($id)
    {
        ProductType::destroy($id);

        return redirect()->route('product_types.index')
                         ->with('success', 'Product Type deleted successfully.');
    }
}