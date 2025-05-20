<!-- resources/views/departments/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista Departmana</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary mb-4">Dodaj novi departman</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Naziv Departmana</th>
                <th>Akcije</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departments as $department)
            <tr>
                <td>{{ $department->NAME }}</td>
                <td>
                    <a href="{{ route('departments.edit', $department->DEPT_ID) }}" class="btn btn-warning btn-sm">Uredi</a>
                    <form action="{{ route('departments.destroy', $department->DEPT_ID) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Obriši</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
