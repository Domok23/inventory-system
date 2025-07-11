@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Employee</h1>
    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="position" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection