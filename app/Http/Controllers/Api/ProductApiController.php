<?php

namespace App\Http\Controllers\Api; // <-- ISPRAVLJENI NAMESPACE!

use App\Http\Controllers\Controller; // Standardni Controller
use App\Models\Product; // Tvoj Product model
use App\Models\ProductType; 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException; // <-- Dodana ova linija za validaciju
use OpenApi\Annotations as OA;

class ProductApiController extends Controller 
{
     /** 
     * @OA\Get(
     * path="/api/products",
     * tags={"Products"},
     * summary="Get all products",
     * description="Returns a list of all products, including their product type.",
     * security={"basicAuth": {}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Product")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup - potrebna Basic HTTP autentifikacija."
     * )
     * )
     */

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

    /** 
     * @OA\Post(
     * path="/api/products",
     * tags={"Products"},
     * summary="Create a new product",
     * description="Creates a new product record.",
     * security={"basicAuth": {}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"PRODUCT_CD", "NAME"},
     * @OA\Property(property="PRODUCT_CD", type="string", example="PROD001"),
     * @OA\Property(property="NAME", type="string", example="Savings Account"),
     * @OA\Property(property="DATE_OFFERED", type="string", format="date", example="2023-01-01", nullable=true),
     * @OA\Property(property="DATE_RETIRED", type="string", format="date", example="2025-12-31", nullable=true),
     * @OA\Property(property="PRODUCT_TYPE_CD", type="string", example="ACC", nullable=true, description="Must exist in Product Types")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Proizvod uspjesno kreiran",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=422,
     * description="Greska validacije",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"PRODUCT_CD": {"The PRODUCT_CD has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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

    /** 
     * @OA\Get(
     * path="/api/products/{product_cd}",
     * tags={"Products"},
     * summary="Get product by code",
     * description="Returns a single product by its code, including its product type.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_CD) of the product to retrieve",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product not found")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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

    /** 
     * @OA\Put(
     * path="/api/products/{product_cd}",
     * tags={"Products"},
     * summary="Update an existing product",
     * description="Updates an existing product record by code.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_CD) of the product to update",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"NAME"},
     * @OA\Property(property="PRODUCT_CD", type="string", example="PROD001", description="Can be optionally updated"),
     * @OA\Property(property="NAME", type="string", example="New Savings Account Name"),
     * @OA\Property(property="DATE_OFFERED", type="string", format="date", example="2023-01-01", nullable=true),
     * @OA\Property(property="DATE_RETIRED", type="string", format="date", example="2026-06-30", nullable=true),
     * @OA\Property(property="PRODUCT_TYPE_CD", type="string", example="CHK", nullable=true, description="Must exist in Product Types")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Product updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product not found")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation Error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"NAME": {"The NAME field is required."}})
     * )
     *  ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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

     /** 
     * @OA\Delete(
     * path="/api/products/{product_cd}",
     * tags={"Products"},
     * summary="Delete a product",
     * description="Deletes a product record by code.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_CD) of the product to delete",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=204,
     * description="Product deleted successfully (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product not found")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */
    
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