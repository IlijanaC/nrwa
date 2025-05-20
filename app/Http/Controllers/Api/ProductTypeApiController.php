<?php

namespace App\Http\Controllers\Api; // VAŽNO: Promijenjen namespace!

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductTypeApiController extends Controller // PROMIJENJENO OVDJE!
{
    // Prikaz svih tipova proizvoda
    public function index()
    {
       $productTypes = ProductType::all();
       return response()->json($productTypes);
    }

    // Forma za dodavanje novog tipa proizvoda (obično nije u API kontrolerima, može biti prazna)
    public function create()
    {
        // Prazno
    }

    // Spremanje novog tipa proizvoda
    public function store(Request $request)
    {
        try {
            $request->validate([
                'PRODUCT_TYPE_CD' => 'required|string|unique:product_types,PRODUCT_TYPE_CD|max:255', // VAŽNO: "product_types" umjesto "product_type" ako je to ime tablice
                'NAME' => 'nullable|string|max:50',
            ]);

            $productType = ProductType::create($request->all());
            return response()->json($productType, 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }
    }

    // Prikaz tipa proizvoda po kodu
    public function show($product_type_cd)
    {
        $productType = ProductType::find($product_type_cd);
        if (!$productType) {
            return response()->json(['message' => 'Product Type not found'], 404);
        }
        return response()->json($productType);
    }

    // Forma za uređivanje postojećeg tipa proizvoda (obično nije u API kontrolerima, može biti prazna)
    public function edit($product_type_cd)
    {
        // Prazno
    }

    // Ažuriranje postojećeg tipa proizvoda
    public function update(Request $request, $product_type_cd)
    {
        try {
            $productType = ProductType::find($product_type_cd);
            if (!$productType) {
                return response()->json(['message' => 'Product Type not found'], 404);
            }

            // Ažuriraj samo NAME, PRODUCT_TYPE_CD ne bi trebao biti u updateu ako je PK
            $request->validate([
                'NAME' => 'nullable|string|max:50',
                // PRODUCT_TYPE_CD ne bi trebao biti ažuriran ovdje ako je primarni ključ i koristi se u ruti
            ]);

            $productType->name = $request->input('NAME'); // Ovo je OK ako je 'name' mala slova
            $productType->save(); // Koristi save() za spremanje promjena

            return response()->json($productType);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }
    }

    // Brisanje tipa proizvoda
    public function destroy($product_type_cd) // Koristi $product_type_cd kao parametar
    {
        $productType = ProductType::find($product_type_cd);
        if (!$productType) {
            return response()->json(['message' => 'Product Type not found'], 404);
        }

        $productType->delete();
        return response()->json(null, 204);
    }
}