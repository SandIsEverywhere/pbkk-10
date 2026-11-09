@extends('adminlte::page')

@section('title', 'Tambah Tugas')

@section('content_header')
    <h1>Tambah Tugas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.tugas.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="title">Judul</label>
                    <input id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required maxlength="255">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="department_id">Departemen</label>
                    <select id="department_id" class="form-select @error('department_id') is-invalid @enderror" name="department_id" required>
                        <option value="">Pilih departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="due_date">Batas Pengumpulan</label>
                    <input id="due_date" class="form-control @error('due_date') is-invalid @enderror" type="date" name="due_date" value="{{ old('due_date') }}">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary" type="submit">Simpan</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.tugas.index') }}">Batal</a>
            </form>
        </div>
    </div>
@stop
