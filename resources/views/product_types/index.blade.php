@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Product Types</h1>
    <a href="{{ route('product_types.create') }}" class="btn btn-primary">Add New Product Type</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Product Type Code</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productTypes as $productType)
            <tr>
                <td>{{ $productType->PRODUCT_TYPE_CD }}</td>
                <td>{{ $productType->NAME }}</td>
                <td>
                    <a href="{{ route('product_types.edit', $productType->PRODUCT_TYPE_CD) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('product_types.destroy', $productType->PRODUCT_TYPE_CD) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
