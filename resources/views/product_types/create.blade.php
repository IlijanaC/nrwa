@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Product Type</h1>
    <form action="{{ route('product_types.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="PRODUCT_TYPE_CD">Product Type Code</label>
            <input type="text" class="form-control" name="PRODUCT_TYPE_CD" required>
        </div>
        <div class="form-group">
            <label for="NAME">Name</label>
            <input type="text" class="form-control" name="NAME">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
