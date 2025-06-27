@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Uredi kupca</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->CUST_ID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="ADDRESS" class="form-label">Adresa</label>
            <input type="text" name="ADDRESS" id="ADDRESS" class="form-control @error('ADDRESS') is-invalid @enderror" value="{{ old('ADDRESS', $customer->ADDRESS) }}" required maxlength="255">
            @error('ADDRESS')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="CITY" class="form-label">Grad</label>
            <input type="text" name="CITY" id="CITY" class="form-control @error('CITY') is-invalid @enderror" value="{{ old('CITY', $customer->CITY) }}" required maxlength="100">
            @error('CITY')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="CUST_TYPE_CD" class="form-label">Tip kupca</label>
            <input type="text" name="CUST_TYPE_CD" id="CUST_TYPE_CD" class="form-control @error('CUST_TYPE_CD') is-invalid @enderror" value="{{ old('CUST_TYPE_CD', $customer->CUST_TYPE_CD) }}" required maxlength="10">
            @error('CUST_TYPE_CD')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="FED_ID" class="form-label">FED ID</label>
            <input type="text" name="FED_ID" id="FED_ID" class="form-control @error('FED_ID') is-invalid @enderror" value="{{ old('FED_ID', $customer->FED_ID) }}" maxlength="50">
            @error('FED_ID')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="POSTAL_CODE" class="form-label">Poštanski broj</label>
            <input type="text" name="POSTAL_CODE" id="POSTAL_CODE" class="form-control @error('POSTAL_CODE') is-invalid @enderror" value="{{ old('POSTAL_CODE', $customer->POSTAL_CODE) }}" maxlength="20">
            @error('POSTAL_CODE')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="STATE" class="form-label">Država</label>
            <input type="text" name="STATE" id="STATE" class="form-control @error('STATE') is-invalid @enderror" value="{{ old('STATE', $customer->STATE) }}" maxlength="50">
            @error('STATE')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Many-to-many za Proizvode --}}
        <div class="mb-3">
            <label for="products" class="form-label">Proizvodi</label>
            <select name="products[]" id="products" class="form-control @error('products') is-invalid @enderror" multiple style="height: 150px;">
                @if(isset($allProducts) && $allProducts->count() > 0)
                    @foreach($allProducts as $product)
                        <option value="{{ $product->PRODUCT_CD }}"
                            {{ in_array($product->PRODUCT_CD, old('products', $associatedProductCds)) ? 'selected' : '' }}>
                            {{ $product->NAME }} ({{ $product->PRODUCT_CD }})
                        </option>
                    @endforeach
                @else
                    <option value="" disabled>Nema dostupnih proizvoda</option>
                @endif
            </select>
            @error('products')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Ažuriraj kupca</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary ms-2">Nazad</a>
    </form>
</div>
@endsection
