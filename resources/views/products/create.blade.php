@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Provjera autorizacije POČETAK --}}
    @auth {{-- Samo prijavljeni korisnici mogu pristupiti --}}
        @can('create products') {{-- Samo oni s dozvolom 'create products' vide formu --}}

            <h1 class="mb-4">Create Product</h1>

            {{-- Prikaz općih validacijskih grešaka ako ih ima više --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Došlo je do greške prilikom unosa:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="PRODUCT_CD" class="form-label">Product Code:</label>
                    <input type="text" id="PRODUCT_CD" name="PRODUCT_CD" class="form-control @error('PRODUCT_CD') is-invalid @enderror" value="{{ old('PRODUCT_CD') }}" required>
                    @error('PRODUCT_CD')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="NAME" class="form-label">Name:</label>
                    <input type="text" id="NAME" name="NAME" class="form-control @error('NAME') is-invalid @enderror" value="{{ old('NAME') }}" required>
                    @error('NAME')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="DATE_OFFERED" class="form-label">Date Offered:</label>
                    <input type="date" id="DATE_OFFERED" name="DATE_OFFERED" class="form-control @error('DATE_OFFERED') is-invalid @enderror" value="{{ old('DATE_OFFERED') }}">
                    @error('DATE_OFFERED')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="DATE_RETIRED" class="form-label">Date Retired:</label>
                    <input type="date" id="DATE_RETIRED" name="DATE_RETIRED" class="form-control @error('DATE_RETIRED') is-invalid @enderror" value="{{ old('DATE_RETIRED') }}">
                    @error('DATE_RETIRED')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="PRODUCT_TYPE_CD" class="form-label">Product Type:</label>
                    <select id="PRODUCT_TYPE_CD" name="PRODUCT_TYPE_CD" class="form-control @error('PRODUCT_TYPE_CD') is-invalid @enderror">
                        <option value="">Select Type</option>
                        @if(isset($productTypes) && $productTypes->count() > 0)
                            @foreach($productTypes as $type)
                                <option value="{{ $type->PRODUCT_TYPE_CD }}" {{ old('PRODUCT_TYPE_CD') == $type->PRODUCT_TYPE_CD ? 'selected' : '' }}>
                                    {{ $type->NAME }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Nema dostupnih tipova proizvoda</option>
                        @endif
                    </select>
                    @error('PRODUCT_TYPE_CD')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Many-to-many za Kupce (dodano) --}}
                <div class="form-group mb-3">
                    <label for="customers" class="form-label">Kupci:</label>
                    <select name="customers[]" id="customers" multiple class="form-control @error('customers') is-invalid @enderror" style="height: 150px;">
                        @if(isset($allCustomers) && $allCustomers->count() > 0)
                            @foreach($allCustomers as $customer)
                                <option value="{{ $customer->CUST_ID }}" 
                                    {{ in_array($customer->CUST_ID, old('customers', $associatedCustomerIds)) ? 'selected' : '' }}>
                                    {{ $customer->ADDRESS }} (ID: {{ $customer->CUST_ID }})
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Nema dostupnih kupaca</option>
                        @endif
                    </select>
                    @error('customers')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary ms-2">Natrag na listu proizvoda</a>
            </form>

        @else {{-- Ako prijavljeni korisnik NEMA dozvolu 'create products' --}}
            <div class="alert alert-danger" role="alert">
                <h1 class="alert-heading">Pristup Odbijen</h1>
                <p>Nemate ovlasti za dodavanje novog proizvoda.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Natrag na listu proizvoda</a>
        @endcan
    @else {{-- Ako korisnik NIJE prijavljen --}}
        <div class="alert alert-warning" role="alert">
            <h1 class="alert-heading">Pristup Odbijen</h1>
            <p>Morate biti prijavljeni da biste dodali novi proizvod.</p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-primary">Prijavi se</a>
    @endauth
    {{-- Provjera autorizacije KRAJ --}}

</div>
@endsection