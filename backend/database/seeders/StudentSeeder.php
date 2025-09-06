<?php
// database/seeders/StudentSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::insert([
            [
                'id' => 1,
                'person_id' => 3,
                'client_id' => 1,
                'email_educacional' => 'alunopedro@gmail.com',
                'ra' => Str::random(8),
            ],
        ]);
    }
}
