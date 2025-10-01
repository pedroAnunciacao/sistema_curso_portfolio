<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            ['id' => 1, 'name' => 'Pedro Cliente', 'email' => 'clientepedro@gmail.com', 'password' => Hash::make('1234Cliente!'), 'person_id' => 1],
            ['id' => 2, 'name' => 'Pedro Professor', 'email' => 'professorpedro@gmail.com', 'password' => Hash::make('1234Professor!'), 'person_id' => 2],
            ['id' => 3, 'name' => 'Pedro Aluno', 'email' => 'alunopedro@gmail.com', 'password' => Hash::make('1234Aluno!'), 'person_id' => 3],
        ]);
    }
}