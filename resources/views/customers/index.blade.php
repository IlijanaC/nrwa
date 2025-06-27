@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista kupaca</h1>

    @can('create customers')
        <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3">Dodaj novog kupca</a>
    @endcan

    @if(session('success'))
        <div class="alert alert-success mb-3" role="alert">{{ session('success') }}</div>
    @endif

    @if($customers->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Adresa</th>
                        <th>Grad</th>
                        <th>Tip kupca</th>
                        <th>FED ID</th>
                        <th>Poštanski broj</th>
                        <th>Država</th>
                        @canany(['edit customers', 'delete customers'])
                            <th>Akcije</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->CUST_ID }}</td>
                        <td>{{ $customer->ADDRESS }}</td>
                        <td>{{ $customer->CITY }}</td>
                        <td>{{ $customer->CUST_TYPE_CD }}</td>
                        <td>{{ $customer->FED_ID }}</td>
                        <td>{{ $customer->POSTAL_CODE }}</td>
                        <td>{{ $customer->STATE }}</td>
                        @canany(['edit customers', 'delete customers'])
                            <td>
                                @can('edit customers')
                                    <a href="{{ route('customers.edit', $customer->CUST_ID) }}" class="btn btn-sm btn-warning">Uredi</a>
                                @endcan
                                @can('delete customers')
                                    <form action="{{ route('customers.destroy', $customer->CUST_ID) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Jeste li sigurni da želite obrisati ovog kupca?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Obriši</button>
                                    </form>
                                @endcan
                            </td>
                        @endcanany
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Nema dostupnih kupaca.</p>
        @auth
            @can('create customers')
                <p><a href="{{ route('customers.create') }}" class="btn btn-info btn-sm">Želite li dodati prvog kupca?</a></p>
            @endcan
        @endauth
    @endif
</div>
@endsection