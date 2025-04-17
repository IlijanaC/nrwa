<!-- resources/views/departments/create.blade.php -->
@extends('layouts.app')
<h1>Dodaj Novi Departman</h1>
<form action="{{ route('departments.store') }}" method="POST">
    @csrf
    <label for="NAME">Naziv Departmana:</label>
    <input type="text" name="NAME" required>
    <button type="submit">Spremi</button>
</form>
