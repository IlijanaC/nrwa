<!-- resources/views/departments/index.blade.php -->
@extends('layouts.app')
<h1>Lista Departmana</h1>
<a href="{{ route('departments.create') }}">Dodaj novi departman</a>
<ul>
    @foreach ($departments as $department)
        <li>
            {{ $department->NAME }}
            <a href="{{ route('departments.edit', $department->DEPT_ID) }}">Uredi</a>
            <form action="{{ route('departments.destroy', $department->DEPT_ID) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Obriši</button>
            </form>
        </li>
    @endforeach
</ul>
