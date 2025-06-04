@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Types</h1>

    {{-- Gumb "Add New Product Type" --}}
    {{-- Prikazujemo ga samo ako je korisnik prijavljen I ima dozvolu 'create product_types' --}}
    @auth
        @can('create product_types')
            <a href="{{ route('product_types.create') }}" class="btn btn-primary mb-3">Add New Product Type</a> {{-- Dodao mb-3 za marginu --}}
        @endcan
    @endauth

    {{-- Provjera postoje li uopće tipovi proizvoda prije nego prikažemo tablicu --}}
    @if(isset($productTypes) && $productTypes->count() > 0)
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Product Type Code</th>
                    <th>Name</th>
                    {{-- Stupac "Actions" prikazujemo samo prijavljenim korisnicima koji imaju bar jednu od dozvola --}}
                    @auth
                        @if(Auth::user()->can('edit product_types') || Auth::user()->can('delete product_types'))
                            <th>Actions</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach ($productTypes as $productType)
                <tr>
                    <td>
                        {{-- Link na show stranicu, dostupan gostima i prijavljenima s 'view product_types' dozvolom --}}
                        @if(Auth::guest() || (Auth::check() && Auth::user()->can('view product_types')))
                            <a href="{{ route('product_types.show', $productType->PRODUCT_TYPE_CD) }}">{{ $productType->PRODUCT_TYPE_CD }}</a>
                        @else
                            {{ $productType->PRODUCT_TYPE_CD }}
                        @endif
                    </td>
                    <td>{{ $productType->NAME }}</td>
                    
                    {{-- Akcije "Edit" i "Delete" --}}
                    @auth {{-- Akcije su dostupne samo prijavljenim korisnicima --}}
                        @if(Auth::user()->can('edit product_types') || Auth::user()->can('delete product_types'))
                        <td>
                            @can('edit product_types')
                                <a href="{{ route('product_types.edit', $productType->PRODUCT_TYPE_CD) }}" class="btn btn-warning btn-sm">Edit</a> {{-- Dodao btn-sm za konzistentnost --}}
                            @endcan

                            {{-- Osigurajte mali razmak ako su oba gumba prikazana --}}
                            @if(Auth::user()->can('edit product_types') && Auth::user()->can('delete product_types'))
                                  {{-- Ili neki CSS za marginu --}}
                            @endif

                            @can('delete product_types')
                                <form action="{{ route('product_types.destroy', $productType->PRODUCT_TYPE_CD) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product type?')">Delete</button> {{-- Dodao btn-sm i onclick confirm --}}
                                </form>
                            @endcan
                        </td>
                        @else
                        {{-- Prazna ćelija ako je stupac Actions prikazan, a korisnik nema ni edit ni delete dozvolu.
                             Alternativno, ako nema dozvola, stupac se uopće neće prikazati zbog gornjeg @if --}}
                        {{-- <td></td> --}}
                        @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No product types found.</p>
        {{-- Opcionalno, ako je korisnik prijavljen i smije kreirati, ponuditi mu da doda prvi --}}
        @auth
            @can('create product_types')
                <p><a href="{{ route('product_types.create') }}" class="btn btn-link">Want to add the first product type?</a></p>
            @endcan
        @endauth
    @endif
</div>
@endsection