<!-- resources/views/products/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        /* Vaši stilovi ostaju neizmijenjeni */
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
        button { /* Stil za delete gumb unutar forme */
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: darkred;
        }
        a { /* Općeniti stil za linkove */
            padding: 5px 10px;
            color: blue;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .add-product-btn { /* Stil za "Add Product" link koji izgleda kao gumb */
            display: inline-block; /* Da bi padding i margin radili kako treba */
            padding: 10px 20px;
            background-color: green;
            color: white !important; /* !important da premosti općeniti 'a' stil ako je potrebno */
            text-decoration: none;
            margin-bottom: 20px;
        }
        .add-product-btn:hover {
            background-color: darkgreen;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <h1>Products</h1>

    <!-- Dodaj proizvod -->
    {{-- Prikazujemo gumb "Add Product" samo prijavljenim korisnicima s dozvolom 'create products' --}}
    @auth
        @can('create products')
            <a href="{{ route('products.create') }}" class="add-product-btn">Add Product</a>
        @endcan
    @endauth

    {{-- Provjera ima li proizvoda prije prikaza tablice --}}
    @if(isset($products) && $products->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Date Offered</th>
                    <th>Date Retired</th>
                    {{-- Stupac "Actions" prikazujemo samo prijavljenim korisnicima koji imaju bar jednu od dozvola --}}
                    @auth
                        @if(Auth::user()->can('edit products') || Auth::user()->can('delete products'))
                            <th>Actions</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        {{-- Link na show stranicu, dostupan gostima i prijavljenima s 'view products' dozvolom --}}
                        {{-- Auth::guest() provjerava je li korisnik gost --}}
                        {{-- Auth::check() provjerava je li prijavljen --}}
                        @if(Auth::guest() || (Auth::check() && Auth::user()->can('view products')))
                            <a href="{{ route('products.show', $product->PRODUCT_CD) }}">{{ $product->PRODUCT_CD }}</a>
                        @else
                            {{ $product->PRODUCT_CD }} {{-- Ako prijavljeni korisnik nema 'view products' dozvolu (malo vjerojatno ako admin/editor/viewer imaju) --}}
                        @endif
                    </td>
                    <td>{{ $product->NAME }}</td>
                    <td>{{ $product->productType->NAME ?? 'N/A' }}</td>
                    <td>{{ $product->DATE_OFFERED ? \Carbon\Carbon::parse($product->DATE_OFFERED)->format('d.m.Y') : '' }}</td>
                    <td>{{ $product->DATE_RETIRED ? \Carbon\Carbon::parse($product->DATE_RETIRED)->format('d.m.Y') : '' }}</td>
                    
                    {{-- Akcije "Uredi" i "Obriši" --}}
                    @auth {{-- Akcije su dostupne samo prijavljenim korisnicima --}}
                        @if(Auth::user()->can('edit products') || Auth::user()->can('delete products'))
                        <td>
                            @can('edit products')
                                <a href="{{ route('products.edit', $product->PRODUCT_CD) }}">Edit</a>
                            @endcan

                            {{-- Osigurajte mali razmak ako su oba gumba prikazana --}}
                            @if(Auth::user()->can('edit products') && Auth::user()->can('delete products'))
                                  {{-- Ili neki CSS za marginu --}}
                            @endif

                            @can('delete products')
                                <form action="{{ route('products.destroy', $product->PRODUCT_CD) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            @endcan
                        </td>
                        @else
                        {{-- Prazna ćelija ako korisnik nema ni 'edit' ni 'delete' dozvolu, a stupac Actions je prikazan
                             (npr. ako ste imali neku treću akciju pa ste je maknuli, a stupac ostao)
                             Alternativno, ako nema dozvola, stupac se uopće neće prikazati zbog gornjeg @if --}}
                        {{-- <td></td> --}} 
                        @endif
                    @endauth
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No products found.</p>
        {{-- Opcionalno, ako je korisnik prijavljen i smije kreirati, ponuditi mu da doda prvi --}}
        @auth
            @can('create products')
                <p><a href="{{ route('products.create') }}" class="add-product-btn" style="font-size: 0.9em; padding: 5px 10px;">Want to add the first product?</a></p>
            @endcan
        @endauth
    @endif

</body>
</html>