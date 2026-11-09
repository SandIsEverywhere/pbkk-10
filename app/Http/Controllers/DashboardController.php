<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = $validated['search'] ?? null;

        $students = User::role('mahasiswa')
            ->with('department')
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('nrp', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', [
            'departmentCount' => Department::count(),
            'studentCount' => User::role('mahasiswa')->count(),
            'tugasCount' => Tugas::count(),
            'students' => $students,
            'search' => $search,
        ]);
    }
}
