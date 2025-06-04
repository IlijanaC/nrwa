<!-- resources/views/departments/edit.blade.php -->
@extends('layouts.app')

{{-- Provjera autorizacije POČETAK --}}
@auth {{-- Samo prijavljeni korisnici mogu pristupiti ovoj stranici --}}
    {{-- Prvo provjeravamo postoji li uopće $department varijabla (za slučaj da je ruta pozvana neispravno) --}}
    {{-- i zatim provjeravamo dozvolu za uređivanje ovog konkretnog departmana --}}
    {{-- Za Spatie $user->can() dovoljna je samo dozvola, ne treba model ako ne koristite Policy klasu s modelom --}}
    @can('edit departments') 

<h1>Uredi Departman</h1>


{{-- Prikaz općih validacijskih grešaka ako ih ima više --}}
        @if ($errors->any())
            <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                <strong>Došlo je do greške:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

<form action="{{ route('departments.update', $department->DEPT_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="NAME">Naziv Departmana:</label>
    <input type="text" name="NAME" value="{{ $department->NAME }}" required>
    @error('NAME')
                    <div style="color: red; font-size: 0.9em; margin-top: 5px;">{{ $message }}</div>
                @enderror
    <button type="submit">Spremi</button>
</form>

@else {{-- Ako prijavljeni korisnik NEMA dozvolu 'edit departments' --}}
        <h1>Pristup Odbijen</h1>
        <p>Nemate ovlasti za uređivanje ovog departmana.</p>
        <a href="{{ route('departments.index') }}">Natrag na listu odjela</a>
    @endcan
@else {{-- Ako korisnik NIJE prijavljen --}}
    <h1>Pristup Odbijen</h1>
    <p>Morate biti prijavljeni da biste uređivali departman.</p>
    <a href="{{ route('login') }}">Prijavi se</a>
@endauth
{{-- Provjera autorizacije KRAJ --}}
