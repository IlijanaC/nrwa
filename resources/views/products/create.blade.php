<h1>Create Product</h1>

<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <label>Product Code:</label>
    <input type="text" name="PRODUCT_CD" required>

    <label>Name:</label>
    <input type="text" name="NAME" required>

    <label>Date Offered:</label>
    <input type="date" name="DATE_OFFERED">

    <label>Date Retired:</label>
    <input type="date" name="DATE_RETIRED">

    <label>Product Type:</label>
    <select name="PRODUCT_TYPE_CD" required>
        <option value="">Select Type</option>
        @foreach($productTypes as $type)
            <option value="{{ $type->PRODUCT_TYPE_CD }}">{{ $type->NAME }}</option>
        @endforeach
    </select>

    <button type="submit">Create</button>
</form>
