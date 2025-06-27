@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('edit product_types') {{-- Samo oni s dozvolom 'edit product_types' vide formu --}}

            <h1>Edit Product Type</h1>

            {{-- Prikaz općih validacijskih grešaka ako ih ima više --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Došlo je do greške prilikom unosa:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('product_types.update', $productType->PRODUCT_TYPE_CD) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Input polje za PRODUCT_TYPE_CD --}}
                <div class="form-group mb-3">
                    <label for="PRODUCT_TYPE_CD_DISPLAY">Product Type Code</label>
                    <input type="text"
                           class="form-control @error('PRODUCT_TYPE_CD') is-invalid @enderror"
                           id="PRODUCT_TYPE_CD_DISPLAY"
                           name="PRODUCT_TYPE_CD"
                           value="{{ old('PRODUCT_TYPE_CD', $productType->PRODUCT_TYPE_CD) }}"
                           readonly>
                    @error('PRODUCT_TYPE_CD')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Input polje za NAME --}}
                <div class="form-group mb-3">
                    <label for="NAME">Name</label>
                    <input type="text"
                           class="form-control @error('NAME') is-invalid @enderror"
                           id="NAME"
                           name="NAME"
                           value="{{ old('NAME', $productType->NAME) }}">
                    @error('NAME')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Submit dugme --}}
                <button type="submit" class="btn btn-primary">Update</button>
            </form>

        @else {{-- Ako korisnik nema dozvolu --}}
            <div class="alert alert-danger" role="alert">
                <h1>Pristup Odbijen</h1>
                <p>Nemate ovlasti za uređivanje ovog tipa proizvoda.</p>
            </div>
            <a href="{{ route('product_types.index') }}" class="btn btn-secondary">Natrag na listu tipova proizvoda</a>
        @endcan

    @else {{-- Ako korisnik nije prijavljen --}}
        <div class="alert alert-warning" role="alert">
            <h1>Pristup Odbijen</h1>
            <p>Morate biti prijavljeni da biste uređivali tip proizvoda.</p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary">Prijavi se</a>
    @endauth

</div>
@endsection
