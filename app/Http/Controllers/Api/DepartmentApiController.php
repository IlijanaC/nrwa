<?php

namespace App\Http\Controllers\Api; // VAŽNO: Promijenjen namespace da odgovara mapi Api

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DepartmentApiController extends Controller // PROMIJENJENO OVDJE!
{
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