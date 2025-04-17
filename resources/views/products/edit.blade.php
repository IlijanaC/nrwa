<h1>Edit Product</h1>

<form method="POST" action="{{ route('products.update', $product->PRODUCT_CD) }}">
    @csrf
    @method('PUT')

    <label>Product Code:</label>
    <input type="text" name="PRODUCT_CD" value="{{ $product->PRODUCT_CD }}" readonly>

    <label>Name:</label>
    <input type="text" name="NAME" value="{{ $product->NAME }}" required>

    <label>Date Offered:</label>
    <input type="date" name="DATE_OFFERED" value="{{ $product->DATE_OFFERED }}">

    <label>Date Retired:</label>
    <input type="date" name="DATE_RETIRED" value="{{ $product->DATE_RETIRED }}">

    <label>Product Type:</label>
    <select name="PRODUCT_TYPE_CD" required>
        <option value="">Select Type</option>
        @foreach($productTypes as $type)
            <option value="{{ $type->PRODUCT_TYPE_CD }}" {{ $type->PRODUCT_TYPE_CD == $product->PRODUCT_TYPE_CD ? 'selected' : '' }}>
                {{ $type->NAME }}
            </option>
        @endforeach
    </select>

    <button type="submit">Update</button>
</form>
