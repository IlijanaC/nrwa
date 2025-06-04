@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('edit product_types') {{-- Samo oni s dozvolom 'edit product_types' vide formu --}}

            <h1>Edit Product Type</h1>

            {{-- PrikazU redu, evo kako bi vaš `product_types/edit.blade.php` izgledao s dodanom autorizacijom i preporučenim UX poboljšanjima, zadržavajući vašu postojeću strukturu i Bootstrap klase.

Pretpostavke:
*    općih validacijskih grešaka ako ih ima više --}}
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
                <div class="form-group mb-3"> {{--Samo korisnici s dozvolom `'edit product_types'` smiju vidjeti ovu formu.
*   Varijabla `$productType` je proslijeđena iz kontrolera.
*   `PRODUCT_TYPE_CD` je primarni Dodao mb-3 za marginu --}}
                    <label for="PRODUCT_TYPE_CD_DISPLAY">Product Type Code</label> {{-- Promijenio ID radi jasnoće, jer je polje readonly --}}
                    {{-- PRODUCT_TYPE_CD je primarni ključ, obično se ne mijenja.
                         Ako ga ne želite slati u requestu jer je već u ruti, maknite 'name' atribut.
                          ključ i vjerojatno se ne mijenja, pa sam ga postavio na `readonly`. Ako ga želite mijenjati, uklonite `readonly` i prilagodite validaciju u kontroleru.

```blade
@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('edit product_types') {{-- Samo oni s dozvolom 'edit product_types' vide formu --}}

            <h1>Edit Product Type</h1>

            {{-- Prikaz općih validacijskih greZa sada ostavljam kako je bilo, ali dodajem readonly.
                         Ako treba biti promjenjiv, maknite readonly i osigurajte validaciju u kontroleru. --}}
                    <input type="text" class="form-control @error('PRODUCT_TYPE_CD') is-invalid @enderror" id="PRODUCT_TYPE_CD_DISPLAY" name="PRODUCT_TYPE_CD" value="{{ $productType->PRODUCT_TYPE_CD }}" readonly {{-- Dodao readonly, maknite 'required' ako je readonly --}}>
                    @error('PRODUCT_TYPE_CD') {{-- Greška za PRODUCT_TYPE_CD ako ga ipak pokušate validirati/mijenjati --}}šaka ako ih ima više --}}
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
                <div class="form-group mb-3"> {{-- Dodao mb-3 za marginu --}}
                    <label for="PRODUCT_TYPE_CD_DISPLAY">Product Type Code</label> {{-- Promijenio ID
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3"> {{-- Dodao mb-3 za marginu --}}
                    <label for="NAME">Name</label>
                    <input type="text" class="form-control @error('NAME') is-invalid @enderror" id="NAME" name="NAME" value="{{ old('NAME', $productType->NAME) }}">
                    @error('NAME')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary da ne bude isti kao name --}}
                    {{-- PRODUCT_TYPE_CD je obično primarni ključ i ne mijenja se, stoga readonly.
                         Vaš originalni kod je imao 'name="PRODUCT_TYPE_CD"' što bi ga slalo u requestu.
                         Ako ga ne želite slati jer je u ruti, možete maknuti name atribut ili ga ostaviti kao readonly za prikaz.
                         Ako je ipak namijenjen za izmjenu, maknite readonly i required. Vaš original je imao required.
                         Za sada ga ostavljam kako">Update</button>
            </form>

        @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'edit product_types' --}}
            <div class="alert alert-danger" role="alert">
                <h1>Pristup Odbijen</h1>
                <p>Nemate ovlasti za uređivanje ovog tipa proizvoda.</p>
            </div>
            <a href="{{ route('product_types.index') }}" class="btn btn-secondary">Natrag na listu tipova proizvoda</a>
        @endcan

    @else {{-- Ako korisnik NIJE prijavljen --}}
        <div class="alert alert-warning" role="alert">
            <h1>Pristup Odbijen</h1>
            <p>Morate biti prijavljeni da biste uređ ste imali, ali s readonly ako je to namjera.
                         Ako je namijenjen za izmjenu, maknite readonly.
                    --}}
                    <input type="text" class="form-control" id="PRODUCT_TYPE_CD_DISPLAY" name="PRODUCT_TYPE_CD_DISPLAY_ONLY" value="{{ $productType->PRODUCT_TYPE_CD }}" readonly>
                    {{-- Ako želite da se PRODUCT_TYPE_CD ipak pošalje (iako je u ruti), a ne želite ga mijenjati:
                    <input type="hidden" name="PRODUCT_ivali tip proizvoda.</p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary">Prijavi se</a>
    @endauth
    {{-- Provjera autorizacije KRAJ --}}

</div>
@endsection@extends('layouts.app')

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
                    {{-- PRODUCT_TYPE_CD je obično primarni ključ i ne mijenja se, stoga readonly.
                         Ako ga ipak treba mijenjati, uklonite 'readonly' i prilagodite validaciju.
                         'name' atribut je potreban ako želite da se njegova vrijednost pošalje (iako je readonly),
                         ili ako ga hvatate u kontroleru iz nekog razloga.
                         Ako je samo za prikaz i već je u ruti, 'name' atribut može biti uklonjen.
                         Za sada ga ostavljamo s 'name' i 'readonly'.
                    --}}
                    <input type="text"
                           class="form-control @error('PRODUCT_TYPE_CD') is-invalid @enderror"
                           id="PRODUCT_TYPE_CD_DISPLAY"
                           name="PRODUCT_TYPE_CD" {{-- Ostavljamo ime ako ga validirate ili koristite --}}
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

            </form> {{-- Kraj glavne forme --}}

        @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'edit product_types' --}}
            <div class="alert alert-danger" role="alert">
                <h1>Pristup Odbijen</h1>
                <p>Nemate ovlasti za uređivanje ovog tipa proizvoda.</p>
            </div>
            <a href="{{ route('product_types.index') }}" class="btn btn-secondary">Natrag na listu tipova proizvoda</a>
        @endcan {{-- Kraj @can --}}

    @else {{-- Ako korisnik NIJE prijavljen --}}
        <div class="alert alert-warning" role="alert">
            <h1>Pristup Odbijen</h1>
            <p>Morate biti prijavljeni da biste uređivali tip proizvoda.</p> {{-- Ispravljena poruka --}}
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary">Prijavi se</a>
    @endauth {{-- Kraj @auth --}}
    {{-- Provjera autorizacije KRAJ --}}

</div> {{-- Kraj .container --}}
@endsection {{-- Kraj @section('content') --}}