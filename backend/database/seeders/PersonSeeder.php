<?php
// database/seeders/PersonSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;

class PersonSeeder extends Seeder
{
    public function run(): void
    {
        Person::insert([
            ['id' => 1, 'name' => 'Pedro Cliente', 'email' => 'clientepedro@gmail.com', 'cpf_cnpj' => '11122233344', 'birth_date' => '1980-01-01'],
            ['id' => 2, 'name' => 'Pedro Professor', 'email' => 'professorpedro@gmail.com', 'cpf_cnpj' => '55566677788', 'birth_date' => '1985-05-10'],
            ['id' => 3, 'name' => 'Pedro Aluno', 'email' => 'alunopedro@gmail.com', 'cpf_cnpj' => '99988877766', 'birth_date' => '2010-09-15'],
        ]);
    }
}
