<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    // Prikaz svih departmana
    public function index()
    {

        if (!Auth::user()->can('view departments')) {
            abort(403, 'Niste ovlašteni za pregled odjela.');
        } 

        $departments = Department::all();  // Dohvaćanje svih departmana iz tablice
        return view('departments.index', compact('departments'));  // Vraća prikaz sa podacima
    }

    // Prikaz forme za dodavanje novog departmana
    public function create()
    {
        if (!Auth::user()->can('create departments')) {
            abort(403, 'Niste ovlašteni za kreiranje odjela.');
        }

        return view('departments.create');
    }

    // Spremanje novog departmana
    public function store(Request $request)
    {
        if (!Auth::user()->can('create departments')) {
            abort(403, 'Niste ovlašteni za spremanje odjela.');
        }

        $validated = $request->validate([
            'NAME' => 'required|max:255',
        ]);

        Department::create($validated);  // Dodavanje novog departmana u bazu
        return redirect()->route('departments.index');  // Preusmjeravanje na listu departmana
    }

    // Prikaz forme za uređivanje departmana
    public function edit($id)
    {
        if (!Auth::user()->can('edit departments')) {
            abort(403, 'Niste ovlašteni za uređivanje ovog odjela.');
        }

        $department = Department::findOrFail($id);  // Dohvat departmana po ID-u
        return view('departments.edit', compact('department'));
    }

    // Ažuriranje postojećeg departmana
    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('edit departments')) {
            abort(403, 'Niste ovlašteni za ažuriranje ovog odjela.');
        }

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
         if (!Auth::user()->can('delete departments')) {
            abort(403, 'Niste ovlašteni za brisanje ovog odjela.');
        }
        
        $department = Department::findOrFail($id);
        $department->delete();  // Brisanje departmana iz baze
        return redirect()->route('departments.index');
    }
}