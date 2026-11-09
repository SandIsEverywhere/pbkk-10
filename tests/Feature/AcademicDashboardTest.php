<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Tugas;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AcademicDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_authorization_and_academic_seeders_create_expected_records(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue(Role::findByName('asisten-dosen')->hasPermissionTo('kelola-nilai'));
        $this->assertDatabaseCount('departments', 3);
    }

    public function test_admin_dashboard_shows_dynamic_statistics_and_is_not_available_to_students(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $department = Department::create(['name' => 'Informatika']);

        $student = User::factory()->create([
            'name' => 'Mahasiswa Dashboard',
            'nrp' => '50242001',
            'department_id' => $department->id,
        ]);
        $student->assignRole('mahasiswa');
        Tugas::create([
            'title' => 'Tugas Algoritma',
            'department_id' => $department->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Akademik')
            ->assertSee('50242001')
            ->assertSee('Mahasiswa')
            ->assertSee('text-bg-primary')
            ->assertViewHas('studentCount', 1)
            ->assertViewHas('departmentCount', 1)
            ->assertViewHas('tugasCount', 1);

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_student_search_matches_name_or_nrp(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $matchedStudent = User::factory()->create([
            'name' => 'Rina Example',
            'nrp' => '50242077',
        ]);
        $matchedStudent->assignRole('mahasiswa');

        $otherStudent = User::factory()->create([
            'name' => 'Other Student',
            'nrp' => '50242088',
        ]);
        $otherStudent->assignRole('mahasiswa');

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['search' => '50242077']))
            ->assertOk()
            ->assertSee('Rina Example')
            ->assertDontSee('Other Student');

        $this->actingAs($admin)
            ->get(route('admin.dashboard', ['search' => 'Rina Example']))
            ->assertOk()
            ->assertSee('50242077')
            ->assertDontSee('Other Student');
    }

    public function test_tugas_filters_are_kept_in_pagination_and_destroy_requires_permission(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $department = Department::create(['name' => 'Informatika']);

        foreach (range(1, 11) as $number) {
            Tugas::create([
                'title' => "Lab assignment {$number}",
                'department_id' => $department->id,
            ]);
        }

        $filters = ['department_id' => $department->id, 'search' => 'Lab'];
        $this->actingAs($admin)
            ->get(route('admin.tugas.index', $filters))
            ->assertOk()
            ->assertSee(route('admin.tugas.index', $filters + ['page' => 2]));

        $tugas = Tugas::first();
        Role::findByName('admin')->revokePermissionTo('kelola-tugas');

        $this->delete(route('admin.tugas.destroy', $tugas))
            ->assertForbidden();

        Role::findByName('admin')->givePermissionTo('kelola-tugas');
        $this->delete(route('admin.tugas.destroy', $tugas))
            ->assertRedirect(route('admin.tugas.index'));

        $this->assertDatabaseMissing('tugas', ['id' => $tugas->id]);
    }
}
