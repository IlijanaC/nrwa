<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Prikaz svih departmana
    public function index()
    {
        $departments = Department::all();  // Dohvaćanje svih departmana iz tablice
        return view('departments.index', compact('departments'));  // Vraća prikaz sa podacima
    }

    // Prikaz forme za dodavanje novog departmana
    public function create()
    {
        return view('departments.create');
    }

    // Spremanje novog departmana
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NAME' => 'required|max:255',
        ]);

        Department::create($validated);  // Dodavanje novog departmana u bazu
        return redirect()->route('departments.index');  // Preusmjeravanje na listu departmana
    }

    // Prikaz forme za uređivanje departmana
    public function edit($id)
    {
        $department = Department::findOrFail($id);  // Dohvat departmana po ID-u
        return view('departments.edit', compact('department'));
    }

    // Ažuriranje postojećeg departmana
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'NAME' => 'required|max:255',
        ]);

        $department = Department::findOrFail($id);  // Dohvat departmana za update
        $department->update($validated);  // Ažuriranje departmana u bazi
        return redirect()->route('departments.index');  // Preusmjeravanje na listu
    }

    // Brisanje departmana
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();  // Brisanje departmana iz baze
        return redirect()->route('departments.index');
    }
}