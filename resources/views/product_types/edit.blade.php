@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product Type</h1>
    <form action="{{ route('product_types.update', $productType->PRODUCT_TYPE_CD) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="PRODUCT_TYPE_CD">Product Type Code</label>
            <input type="text" class="form-control" name="PRODUCT_TYPE_CD" value="{{ $productType->PRODUCT_TYPE_CD }}" required>
        </div>
        <div class="form-group">
            <label for="NAME">Name</label>
            <input type="text" class="form-control" name="NAME" value="{{ $productType->NAME }}">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
