<?php

namespace App\Http\Controllers\Api; // <-- ISPRAVLJENI NAMESPACE!

use App\Http\Controllers\Controller; // Standardni Controller
use App\Models\Product; // Tvoj Product model
use App\Models\ProductType; // Možda ti ne treba ovdje ako ga samo koristiš u validaciji.
                            // Ali ok je da stoji ako ti je tako lakše pratiti.
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // <-- Dodana ova linija za validaciju


class ProductApiController extends Controller // <-- ISPRAVLJENO IME KLASE!
{
    // Prikaz svih proizvoda
    public function index()
    {
        $products = Product::with('productType')->get(); // Uključi relaciju
        return response()->json($products);
    }

    // Nema potrebe za 'create' metodom u API kontroleru.
    // Metode 'create' i 'edit' se generiraju sa Route::resource, ali se u apiResource ne koriste.
    // Možeš ih obrisati ili ostaviti prazne, neće smetati.
    // public function create() { /* ... */ }

    // Spremanje novog proizvoda
    public function store(Request $request)
    {
        try {
            $request->validate([
                // <-- VAŽNO: Imena tablica i kolona u unique/exists pravilima!
                // Ako ti je tablica 'products' i Primary Key je 'PRODUCT_CD', onda je ovo ispravno.
                'PRODUCT_CD' => 'required|unique:products,PRODUCT_CD|max:10',
                'NAME' => 'required|max:50',
                'DATE_OFFERED' => 'nullable|date',
                'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
                // Ako ti je tablica 'product_types' i ključ je 'PRODUCT_TYPE_CD'
                'PRODUCT_TYPE_CD' => 'nullable|exists:product_types,PRODUCT_TYPE_CD'
            ]);

            $product = Product::create($request->all());
            // Ako želiš vratiti i productType relaciju, dohvati je nakon kreiranja
            $product->load('productType');
            return response()->json($product, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Prikaz određenog proizvoda
    // Ako koristiš PRODUCT_CD kao rutni parametar, onda ga trebaš koristiti ovdje.
    // Laravelov Route Model Binding može raditi s prilagođenim ključem ako si ga podesila u modelu.
    // Ali najsigurnije je ručno pronaći ako je primarni ključ nešto drugo od 'id'.
    public function show($product_cd) // <-- Promijenjen parametar da bude jasnije
    {
        $product = Product::with('productType')->where('PRODUCT_CD', $product_cd)->first(); // <-- Promijenjeno pretraživanje
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Nema potrebe za 'edit' metodom u API kontroleru.
    // public function edit($id) { /* ... */ }

    // Ažuriranje proizvoda
    public function update(Request $request, $product_cd) // <-- Promijenjen parametar
    {
        try {
            $product = Product::where('PRODUCT_CD', $product_cd)->first(); // <-- Promijenjeno pretraživanje
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            // Validacija za ažuriranje - 'unique' pravilo treba ignorirati trenutni resurs.
            // Ako PRODUCT_CD nije obavezno za ažuriranje, koristi 'sometimes'
            $request->validate([
                'PRODUCT_CD' => 'sometimes|unique:products,PRODUCT_CD,' . $product->PRODUCT_CD . ',PRODUCT_CD|max:10', // Imenovanje kolone
                'NAME' => 'required|max:50',
                'DATE_OFFERED' => 'nullable|date',
                'DATE_RETIRED' => 'nullable|date|after_or_equal:DATE_OFFERED',
                'PRODUCT_TYPE_CD' => 'nullable|exists:product_types,PRODUCT_TYPE_CD' // Imenovanje tablice
            ]);

            $product->update($request->all());
            $product->load('productType'); // Ponovno učitaj relaciju nakon ažuriranja
            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }
    }

    // Brisanje proizvoda
    public function destroy($product_cd) // <-- Promijenjen parametar
    {
        $product = Product::where('PRODUCT_CD', $product_cd)->first(); // <-- Promijenjeno pretraživanje
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json(null, 204);
    }
}