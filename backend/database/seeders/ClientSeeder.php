<?php
// database/seeders/ClientSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::insert([
            ['id' => 1, 'person_id' => 1],
        ]);
    }
}
