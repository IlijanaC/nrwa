@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Products</h1>

    {{-- Poruke o uspjehu (npr. nakon kreiranja/ažuriranja/brisanja) --}}
    @if(session('success'))
        <div class="alert alert-success mb-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Dodaj proizvod -->
    {{-- Prikazujemo gumb "Add Product" samo prijavljenim korisnicima s dozvolom 'create products' --}}
    @auth
        @can('create products')
            <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
        @endcan
    @endauth

    {{-- Provjera ima li proizvoda prije prikaza tablice --}}
    @if(isset($products) && $products->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Date Offered</th>
                        <th>Date Retired</th>
                        {{-- Stupac "Actions" prikazujemo samo prijavljenim korisnicima koji imaju bar jednu od dozvola --}}
                        @auth
                            @if(Auth::user()->can('edit products') || Auth::user()->can('delete products'))
                                <th>Actions</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                {{-- Link na show stranicu, dostupan gostima i prijavljenima s 'view products' dozvolom --}}
                                @if(Auth::guest() || (Auth::check() && Auth::user()->can('view products')))
                                    <a href="{{ route('products.show', $product->PRODUCT_CD) }}">{{ $product->PRODUCT_CD }}</a>
                                @else
                                    {{ $product->PRODUCT_CD }}
                                @endif
                            </td>
                            <td>{{ $product->NAME }}</td>
                            <td>{{ $product->productType->NAME ?? 'N/A' }}</td>
                            <td>{{ $product->DATE_OFFERED ? \Carbon\Carbon::parse($product->DATE_OFFERED)->format('d.m.Y') : '' }}</td>
                            <td>{{ $product->DATE_RETIRED ? \Carbon\Carbon::parse($product->DATE_RETIRED)->format('d.m.Y') : '' }}</td>
                            
                            {{-- Akcije "Uredi" i "Obriši" --}}
                            @auth {{-- Akcije su dostupne samo prijavljenim korisnicima --}}
                                @if(Auth::user()->can('edit products') || Auth::user()->can('delete products'))
                                <td>
                                    @can('edit products')
                                        <a href="{{ route('products.edit', $product->PRODUCT_CD) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endcan

                                    @can('delete products')
                                        <form action="{{ route('products.destroy', $product->PRODUCT_CD) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                                @endif
                            @endauth
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No products found.</p>
        {{-- Opcionalno, ako je korisnik prijavljen i smije kreirati, ponuditi mu da doda prvi --}}
        @auth
            @can('create products')
                <p><a href="{{ route('products.create') }}" class="btn btn-info btn-sm">Want to add the first product?</a></p>
            @endcan
        @endauth
    @endif
</div>
@endsection
