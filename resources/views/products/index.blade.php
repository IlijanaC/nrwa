<!-- resources/views/products/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        /* Osnovni stilovi za tablicu */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: darkred;
        }

        a {
            padding: 5px 10px;
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Stil za dugme za dodavanje proizvoda */
        .add-product-btn {
            padding: 10px 20px;
            background-color: green;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .add-product-btn:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

    <h1>Products</h1>

    <!-- Dodaj proizvod -->
    <a href="{{ route('products.create') }}" class="add-product-btn">Add Product</a>

    <!-- Prikaz proizvoda u tablici -->
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Date Offered</th>
                <th>Date Retired</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->PRODUCT_CD }}</td>
                <td>{{ $product->NAME }}</td>
                <td>{{ $product->productType->NAME ?? 'N/A' }}</td>
                <td>{{ $product->DATE_OFFERED }}</td>
                <td>{{ $product->DATE_RETIRED }}</td>
                <td>
                    <!-- Edit gumb -->
                    <a href="{{ route('products.edit', $product->PRODUCT_CD) }}">Edit</a>

                    <!-- Delete gumb -->
                    <form action="{{ route('products.destroy', $product->PRODUCT_CD) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
