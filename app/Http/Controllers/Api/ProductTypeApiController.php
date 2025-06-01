<?php

namespace App\Http\Controllers\Api; 

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class ProductTypeApiController extends Controller 
{

     /** 
     * @OA\Get(
     * path="/api/product_types",
     * tags={"Product Types"},
     * summary="Get all product types",
     * description="Returns a list of all product types.",
     * security={"basicAuth": {}},
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ProductType")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup - potrebna Basic HTTP autentifikacija."
     * )
     * )
     */

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

    /** 
     * @OA\Post(
     * path="/api/product_types",
     * tags={"Product Types"},
     * summary="Create a new product type",
     * description="Creates a new product type record.",
     * security={"basicAuth": {}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"PRODUCT_TYPE_CD"},
     * @OA\Property(property="PRODUCT_TYPE_CD", type="string", example="LOAN"),
     * @OA\Property(property="NAME", type="string", example="Loan Product Type", nullable=true),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Product Type created successfully",
     * @OA\JsonContent(ref="#/components/schemas/ProductType")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation Error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"PRODUCT_TYPE_CD": {"The PRODUCT_TYPE_CD has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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

    /** 
     * @OA\Get(
     * path="/api/product_types/{product_type_cd}",
     * tags={"Product Types"},
     * summary="Get product type by code",
     * description="Returns a single product type by its code.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_type_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_TYPE_CD) of the product type to retrieve",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/ProductType")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product Type not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product Type not found")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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
    
    /** 
     * @OA\Put(
     * path="/api/product_types/{product_type_cd}",
     * tags={"Product Types"},
     * summary="Update an existing product type",
     * description="Updates an existing product type record by code.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_type_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_TYPE_CD) of the product type to update",
     * @OA\Schema(type="string")
     * ),
     * @OA\RequestBody(
     * required=false, 
     * @OA\JsonContent(
     * @OA\Property(property="NAME", type="string", example="New Loan Product Type Name", nullable=true),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Product Type updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/ProductType")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product Type not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product Type not found")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"errors": {"NAME": {"The NAME field must be a string."}}})
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */
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

    /** 
     * @OA\Delete(
     * path="/api/product_types/{product_type_cd}",
     * tags={"Product Types"},
     * summary="Delete a product type",
     * description="Deletes a product type record by code.",
     * security={"basicAuth": {}},
     * @OA\Parameter(
     * name="product_type_cd",
     * in="path",
     * required=true,
     * description="Code (PRODUCT_TYPE_CD) of the product type to delete",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=204,
     * description="Product Type deleted successfully (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Product Type not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Product Type not found")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Neautoriziran pristup"
     * )
     * )
     */

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