<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ContactType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $contactsTypes = [
            ['id' => ContactType::Email, 'name' => 'E-mail'],
            ['id' => ContactType::Landline, 'name' => 'Telefone fixo'],
            ['id' => ContactType::CommercialPhone, 'name' => 'Telefone comercial'],
            ['id' => ContactType::Cellphone, 'name' => 'Celular'],
        ];

        DB::table('contacts_types')->insert($contactsTypes);
    }
}
