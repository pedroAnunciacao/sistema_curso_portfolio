<?php
// database/seeders/TeacherSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        Teacher::insert([
            ['id' => 1, 'person_id' => 2, 'client_id' => 1],
        ]);
    }
}
