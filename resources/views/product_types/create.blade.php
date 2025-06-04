@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('create product_types') {{-- Samo oni s dozvolom 'create product_types' vide formu --}}

            <h1>Create New Product Type</h1>

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

            <form action="{{ route('product_types.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3"> {{-- Dodao mb-3 za marginu --}}
                    <label for="PRODUCT_TYPE_CD">Product Type Code</label>
                    <input type="text" class="form-control @error('PRODUCT_TYPE_CD') is-invalid @enderror" id="PRODUCT_TYPE_CD" name="PRODUCT_TYPE_CD" value="{{ old('PRODUCT_TYPE_CD') }}" required>
                    @error('PRODUCT_TYPE_CD')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3"> {{-- Dodao mb-3 za marginu --}}
                    <label for="NAME">Name</label>
                    <input type="text" class="form-control @error('NAME') is-invalid @enderror" id="NAME" name="NAME" value="{{ old('NAME') }}">
                    @error('NAME')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'create product_types' --}}
            <div class="alert alert-danger" role="alert">
                <h1>Pristup Odbijen</h1>
                <p>Nemate ovlasti za dodavanje novog tipa proizvoda.</p>
            </div>
            <a href="{{ route('product_types.index') }}" class="btn btn-secondary">Natrag na listu tipova proizvoda</a>
        @endcan
    @else {{-- Ako korisnik NIJE prijavljen --}}
        <div class="alert alert-warning" role="alert">
            <h1>Pristup Odbijen</h1>
            <p>Morate biti prijavljeni da biste dodali novi tip proizvoda.</p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary">Prijavi se</a>
    @endauth
    {{-- Provjera autorizacije KRAJ --}}

</div>
@endsection