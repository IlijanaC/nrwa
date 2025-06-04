<!-- resources/views/departments/create.blade.php -->
@extends('layouts.app')

{{-- Provjera autorizacije POČETAK --}}
@auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
    @can('create departments') {{-- Samo oni s dozvolom 'create departments' vide formu --}}

<h1>Dodaj Novi Departman</h1>
<form action="{{ route('departments.store') }}" method="POST">
    @csrf
    <label for="NAME">Naziv Departmana:</label>
    <input type="text" name="NAME" required>
    <button type="submit">Spremi</button>
</form>

  @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'create departments' --}}
        <h1>Pristup Odbijen</h1>
        <p>Nemate ovlasti za dodavanje novog departmana.</p>
        {{-- Opcionalno, link za povratak --}}
        <a href="{{ route('departments.index') }}">Natrag na listu odjela</a>
    @endcan
@else {{-- Ako korisnik NIJE prijavljen --}}
    <h1>Pristup Odbijen</h1>
    <p>Morate biti prijavljeni da biste dodali novi departman.</p>
    <a href="{{ route('login') }}">Prijavi se</a>
@endauth
{{-- Provjera autorizacije KRAJ --}}
