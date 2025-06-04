{{-- Ako koristite osnovni layout, dodajte @extends --}}
{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
{{-- <div class="container"> --}}

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('edit products') {{-- Samo oni s dozvolom 'edit products' vide formu --}}
                                {{-- Za @can direktivu, Spatie će provjeriti ima li korisnik dozvolu. --}}
                                {{-- Ako biste koristili Laravel Policy, pisali biste @can('update', $product) --}}

            <h1>Edit Product</h1>

            {{-- Prikaz općih validacijskih grešaka ako ih ima više --}}
            @if ($errors->any())
                <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
                    <strong>Došlo je do greške prilikom unosa:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('products.update', $product->PRODUCT_CD) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 10px;">
                    <label for="PRODUCT_CD_DISPLAY">Product Code:</label><br> {{-- Promijenio sam ID da ne bude isti kao name --}}
                    <input type="text" id="PRODUCT_CD_DISPLAY" name="PRODUCT_CD_DISPLAY" value="{{ $product->PRODUCT_CD }}" readonly>
                    {{-- Skriveno polje za slanje PRODUCT_CD ako ga želite zadržati u requestu, iako je već u ruti --}}
                    {{-- <input type="hidden" name="PRODUCT_CD" value="{{ $product->PRODUCT_CD }}"> --}}
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="NAME">Name:</label><br>
                    <input type="text" id="NAME" name="NAME" value="{{ old('NAME', $product->NAME) }}" required>
                    @error('NAME')
                        <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="DATE_OFFERED">Date Offered:</label><br>
                    <input type="date" id="DATE_OFFERED" name="DATE_OFFERED" value="{{ old('DATE_OFFERED', $product->DATE_OFFERED) }}">
                    @error('DATE_OFFERED')
                        <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="DATE_RETIRED">Date Retired:</label><br>
                    <input type="date" id="DATE_RETIRED" name="DATE_RETIRED" value="{{ old('DATE_RETIRED', $product->DATE_RETIRED) }}">
                    @error('DATE_RETIRED')
                        <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 10px;">
                    <label for="PRODUCT_TYPE_CD">Product Type:</label><br>
                    <select id="PRODUCT_TYPE_CD" name="PRODUCT_TYPE_CD" {{-- Uklonio sam required jer je u kontroleru nullable --}}>
                        <option value="">Select Type</option>
                        @if(isset($productTypes) && $productTypes->count() > 0)
                            @foreach($productTypes as $type)
                                <option value="{{ $type->PRODUCT_TYPE_CD }}" {{ old('PRODUCT_TYPE_CD', $product->PRODUCT_TYPE_CD) == $type->PRODUCT_TYPE_CD ? 'selected' : '' }}>
                                    {{ $type->NAME }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Nema dostupnih tipova proizvoda</option>
                        @endif
                    </select>
                    @error('PRODUCT_TYPE_CD')
                        <div style="color: red; font-size: 0.9em;">{{ $message }}</div>
                    @enderror 
                </div>

                <button type="submit">Update</button>
            </form>

        @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'edit products' --}}
            <h1>Pristup Odbijen</h1>
            <p>Nemate ovlasti za uređivanje ovog proizvoda.</p>
            <a href="{{ route('products.index') }}">Natrag na listu proizvoda</a>
        @endcan
    @else {{-- Ako korisnik NIJE prijavljen --}}
        <h1>Pristup Odbijen</h1>
        <p>Morate biti prijavljeni da biste uređivali proizvod.</p>
        <a href="{{ route('login') }}">Prijavi se</a>
    @endauth
    {{-- Provjera autorizacije KRAJ --}}

{{-- </div> --}}
{{-- @endsection --}}