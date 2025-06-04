<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductTypeController extends Controller
{
    // Prikaz svih tipova proizvoda
    public function index()
    {
        if (Auth::check() && !Auth::user()->can('view product_types')) {
            // Kao i kod ProductController@index, ova situacija se ne bi trebala desiti
            // ako su 'viewer' i 'editor' uloge ispravno postavljene.
            // abort(403, 'Niste ovlašteni za pregled tipova proizvoda.');
        }

        $productTypes = ProductType::all();
        return view('product_types.index', compact('productTypes'));
    }

    // Forma za dodavanje novog tipa proizvoda
    public function create()
    {
        if (!Auth::user()->can('create product_types')) {
            abort(403, 'Niste ovlašteni za kreiranje tipa proizvoda.');
        }

        return view('product_types.create');
    }

    // In App\Http\Controllers\ProductTypeController.php

// ... ostale metode ...


    // Spremanje novog tipa proizvoda
    public function store(Request $request)
    {
         if (!Auth::user()->can('create product_types')) {
            abort(403, 'Niste ovlašteni za spremanje tipa proizvoda.');
        }

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
        if (!Auth::user()->can('edit product_types')) {
            abort(403, 'Niste ovlašteni za uređivanje ovog tipa proizvoda.');
        }

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
    if (!Auth::user()->can('edit product_types')) {
            abort(403, 'Niste ovlašteni za ažuriranje ovog tipa proizvoda.');
        }
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
         if (!Auth::user()->can('delete product_types')) {
            abort(403, 'Niste ovlašteni za brisanje ovog tipa proizvoda.');
        }
        ProductType::destroy($id);

        return redirect()->route('product_types.index')
                         ->with('success', 'Product Type deleted successfully.');
    }
}