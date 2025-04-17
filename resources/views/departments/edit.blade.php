<!-- resources/views/departments/edit.blade.php -->
@extends('layouts.app')
<h1>Uredi Departman</h1>
<form action="{{ route('departments.update', $department->DEPT_ID) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="NAME">Naziv Departmana:</label>
    <input type="text" name="NAME" value="{{ $department->NAME }}" required>
    <button type="submit">Spremi</button>
</form>
