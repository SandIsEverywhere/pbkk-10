@extends('adminlte::page')

@section('title', 'Dashboard Akademik')

@section('content_header')
    <h1>Dashboard Akademik</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $studentCount }}</h3>
                    <p>Mahasiswa</p>
                </div>
                <i class="small-box-icon bi bi-people"></i>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $departmentCount }}</h3>
                    <p>Departemen</p>
                </div>
                <i class="small-box-icon bi bi-building"></i>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $tugasCount }}</h3>
                    <p>Tugas</p>
                </div>
                <i class="small-box-icon bi bi-journal-text"></i>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Mahasiswa</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="visually-hidden" for="student-search">Cari nama atau NRP</label>
                    <input id="student-search" class="form-control" type="search" name="search" value="{{ $search }}" placeholder="Cari nama atau NRP">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NRP</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->nrp ?? '-' }}</td>
                                <td>{{ $student->department?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada mahasiswa yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $students->links('pagination::bootstrap-5') }}
        </div>
    </div>
@stop
