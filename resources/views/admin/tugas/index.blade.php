@extends('adminlte::page')

@section('title', 'Kelola Tugas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Kelola Tugas</h1>
        <a class="btn btn-primary" href="{{ route('admin.tugas.create') }}">Tambah Tugas</a>
    </div>
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.tugas.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="visually-hidden" for="department-filter">Departemen</label>
                    <select id="department-filter" class="form-select" name="department_id">
                        <option value="">Semua departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected(($filters['department_id'] ?? '') == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="visually-hidden" for="task-search">Cari tugas</label>
                    <input id="task-search" class="form-control" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari judul atau deskripsi">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.tugas.index') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Departemen</th>
                            <th>Batas Pengumpulan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugas as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->title }}</strong>
                                    @if ($item->description)
                                        <div class="text-muted">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->department->name }}</td>
                                <td>{{ $item->due_date?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.tugas.destroy', $item) }}" onsubmit="return confirm('Hapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada tugas yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tugas->links('pagination::bootstrap-5') }}
        </div>
    </div>
@stop
