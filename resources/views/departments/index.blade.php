<!-- resources/views/departments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista Departmana</h1>

    {{-- Gumb "Dodaj novi departman" --}}
    {{-- Prikazujemo ga samo ako je korisnik prijavljen I ima dozvolu 'create departments' --}}
    @auth
        @can('create departments')
            <a href="{{ route('departments.create') }}" class="btn btn-primary mb-4">Dodaj novi departman</a>
        @endcan
    @endauth

    {{-- Provjera postoje li uopće departmani prije nego prikažemo tablicu --}}
    @if($departments->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Naziv Departmana</th>
                    {{-- Stupac "Akcije" prikazujemo samo ako je korisnik prijavljen --}}
                    {{-- I ako ima BAREM JEDNU od dozvola za uređivanje ili brisanje --}}
                    @auth
                        @if(Auth::user()->can('edit departments') || Auth::user()->can('delete departments'))
                            <th>Akcije</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                <tr>
                    <td>
                        {{-- Naziv departmana je vidljiv svima koji vide ovu listu --}}
                        {{-- Ako imate show stranicu, možete dodati link ovdje uz provjeru 'view departments' --}}
                        @auth
                            @can('view departments') {{-- Pretpostavka da 'view departments' dozvola postoji i dodijeljena je ulogama koje smiju vidjeti detalje --}}
                                <a href="{{ route('departments.show', $department->DEPT_ID) }}">{{ $department->NAME }}</a>
                            @else
                                {{ $department->NAME }}
                            @endcan
                        @else
                            {{-- Ako bi gosti mogli vidjeti, ali za departments vjerojatno ne --}}
                            {{ $department->NAME }}
                        @endauth
                    </td>

                    {{-- Akcije "Uredi" i "Obriši" --}}
                    @auth
                        @if(Auth::user()->can('edit departments') || Auth::user()->can('delete departments'))
                        <td>
                            @can('edit departments')
                                <a href="{{ route('departments.edit', $department->DEPT_ID) }}" class="btn btn-warning btn-sm">Uredi</a>
                            @endcan

                            {{-- Osigurajte mali razmak ako su oba gumba prikazana --}}
                            @if(Auth::user()->can('edit departments') && Auth::user()->can('delete departments'))
                                  {{-- Ili neki CSS za marginu --}}
                            @endif

                            @can('delete departments')
                                <form action="{{ route('departments.destroy', $department->DEPT_ID) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Jeste li sigurni da želite obrisati ovaj odjel?')">Obriši</button>
                                </form>
                            @endcan
                        </td>
                        @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Trenutno nema unesenih departmana.</p>
        {{-- Opcionalno, ako korisnik smije kreirati, ponuditi mu da doda prvi --}}
        @auth
            @can('create departments')
                <p><a href="{{ route('departments.create') }}">Želite li dodati prvi departman?</a></p>
            @endcan
        @endauth
    @endif
</div>
@endsection