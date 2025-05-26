<?php

namespace App\Http\Controllers\Api; // VAŽNO: Promijenjen namespace da odgovara mapi Api

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="Bank Project API Documentation",
 * description="API endpoints for managing departments, products, and product types in the Bank Project.",
 * @OA\Contact(
 * email="tvoj.email@example.com"
 * ),
 * @OA\License(
 * name="Apache 2.0",
 * url="http://www.apache.org/licenses/LICENSE-2.0.html"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Bank Project API Server"
 * )
 *
 * @OA\Tag(
 * name="Departments",
 * description="API Endpoints of Departments"
 * )
 * @OA\Tag(
 * name="Product Types",
 * description="API Endpoints of Product Types"
 * )
 * @OA\Tag(
 * name="Products",
 * description="API Endpoints of Products"
 * )
 */

class DepartmentApiController extends Controller 
{

     /**
     * @OA\Get(
     * path="/api/departments",
     * tags={"Departments"},
     * summary="Get all departments",
     * description="Returns a list of all departments.",
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Department")
     * )
     * )
     * )
     */

    // Prikaz svih departmana
    public function index()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    // Prikaz forme za dodavanje novog departmana (API kontroler obično nema create/edit metode)
    public function create()
    {
        // Ova metoda je obično prazna u --api kontrolerima.
        // Možeš je ukloniti ili ostaviti praznu ako se ne koristi.
    }

    /**
     * @OA\Post(
     * path="/api/departments",
     * tags={"Departments"},
     * summary="Create a new department",
     * description="Creates a new department record.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"NAME"},
     * @OA\Property(property="NAME", type="string", example="Finance"),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Department created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation Error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"NAME": {"The NAME field is required."}})
     * )
     * )
     * )
     */

    // Spremanje novog departmana
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'NAME' => 'required|string|max:255',
            ]);

            $department = Department::create($validated);
            return response()->json($department, 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422); // 422 Unprocessable Entity
        }
    }

      /**
     * @OA\Get(
     * path="/api/departments/{id}",
     * tags={"Departments"},
     * summary="Get department by ID",
     * description="Returns a single department by its ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the department to retrieve",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=404,
     * description="Department not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Department not found")
     * )
     * )
     * )
     */

    // Prikaz departmana po ID-u
    public function show($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404); // 404 Not Found
        }
        return response()->json($department);
    }

    // Prikaz forme za uređivanje departmana (API kontroler obično nema create/edit metode)
    public function edit($id)
    {
        // Ova metoda je obično prazna u --api kontrolerima.
        // Možeš je ukloniti ili ostaviti praznu ako se ne koristi.
    }

      /**
     * @OA\Put(
     * path="/api/departments/{id}",
     * tags={"Departments"},
     * summary="Update an existing department",
     * description="Updates an existing department record by ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the department to update",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"NAME"},
     * @OA\Property(property="NAME", type="string", example="Human Resources"),
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Department updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=404,
     * description="Department not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Department not found")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation Error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Validation Error"),
     * @OA\Property(property="errors", type="object", example={"NAME": {"The NAME field is required."}})
     * )
     * )
     * )
     */ 

    // Ažuriranje postojećeg departmana
    public function update(Request $request, $id)
    {
        try {
            $department = Department::find($id);
            if (!$department) {
                return response()->json(['message' => 'Department not found'], 404);
            }

            $validated = $request->validate([
                'NAME' => 'required|string|max:255',
            ]);

            $department->update($validated);
            return response()->json($department); // 200 OK
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }
    }

        /**
     * @OA\Delete(
     * path="/api/departments/{id}",
     * tags={"Departments"},
     * summary="Delete a department",
     * description="Deletes a department record by ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the department to delete",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Department deleted successfully (No Content)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Department not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Department not found")
     * )
     * )
     * )
     */
    
    // Brisanje departmana
    public function destroy($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        $department->delete();
        return response()->json(null, 204); // 204 No Content
    }
}