<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TugasController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $tugas = Tugas::query()
            ->with('department')
            ->when($filters['department_id'] ?? null, function (Builder $query, int $departmentId): void {
                $query->where('department_id', $departmentId);
            })
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tugas.index', [
            'tugas' => $tugas,
            'departments' => Department::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.tugas.create', [
            'departments' => Department::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'due_date' => ['nullable', 'date'],
        ]);

        Tugas::create($validated);

        return redirect()->route('admin.tugas.index')->with('status', 'Tugas berhasil ditambahkan.');
    }

    public function destroy(Tugas $tugas): RedirectResponse
    {
        $tugas->delete();

        return redirect()->route('admin.tugas.index')->with('status', 'Tugas berhasil dihapus.');
    }
}
