<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $states = [
            ['id' => 1, 'name' => 'Acre', 'abbreviation' => 'AC', 'code' => 12, 'area_codes' => '[68]'],
            ['id' => 2, 'name' => 'Alagoas', 'abbreviation' => 'AL', 'code' => 27, 'area_codes' => '[82]'],
            ['id' => 3, 'name' => 'Amazonas', 'abbreviation' => 'AM', 'code' => 13, 'area_codes' => '[97,92]'],
            ['id' => 4, 'name' => 'Amapá', 'abbreviation' => 'AP', 'code' => 16, 'area_codes' => '[96]'],
            ['id' => 5, 'name' => 'Bahia', 'abbreviation' => 'BA', 'code' => 29, 'area_codes' => '[77,75,73,74,71]'],
            ['id' => 6, 'name' => 'Ceará', 'abbreviation' => 'CE', 'code' => 23, 'area_codes' => '[88,85]'],
            ['id' => 7, 'name' => 'Distrito Federal', 'abbreviation' => 'DF', 'code' => 53, 'area_codes' => '[61]'],
            ['id' => 8, 'name' => 'Espírito Santo', 'abbreviation' => 'ES', 'code' => 32, 'area_codes' => '[28,27]'],
            ['id' => 9, 'name' => 'Goiás', 'abbreviation' => 'GO', 'code' => 52, 'area_codes' => '[62,64,61]'],
            ['id' => 10, 'name' => 'Maranhão', 'abbreviation' => 'MA', 'code' => 21, 'area_codes' => '[99,98]'],
            ['id' => 11, 'name' => 'Minas Gerais', 'abbreviation' => 'MG', 'code' => 31, 'area_codes' => '[34,37,31,33,35,38,32]'],
            ['id' => 12, 'name' => 'Mato Grosso do Sul', 'abbreviation' => 'MS', 'code' => 50, 'area_codes' => '[67]'],
            ['id' => 13, 'name' => 'Mato Grosso', 'abbreviation' => 'MT', 'code' => 51, 'area_codes' => '[65,66]'],
            ['id' => 14, 'name' => 'Pará', 'abbreviation' => 'PA', 'code' => 15, 'area_codes' => '[91,94,93]'],
            ['id' => 15, 'name' => 'Paraíba', 'abbreviation' => 'PB', 'code' => 25, 'area_codes' => '[83]'],
            ['id' => 16, 'name' => 'Pernambuco', 'abbreviation' => 'PE', 'code' => 26, 'area_codes' => '[81,87]'],
            ['id' => 17, 'name' => 'Piauí', 'abbreviation' => 'PI', 'code' => 22, 'area_codes' => '[89,86]'],
            ['id' => 18, 'name' => 'Paraná', 'abbreviation' => 'PR', 'code' => 41, 'area_codes' => '[43,41,42,44,45,46]'],
            ['id' => 19, 'name' => 'Rio de Janeiro', 'abbreviation' => 'RJ', 'code' => 33, 'area_codes' => '[24,22,21]'],
            ['id' => 20, 'name' => 'Rio Grande do Norte', 'abbreviation' => 'RN', 'code' => 24, 'area_codes' => '[84]'],
            ['id' => 21, 'name' => 'Rondônia', 'abbreviation' => 'RO', 'code' => 11, 'area_codes' => '[69]'],
            ['id' => 22, 'name' => 'Roraima', 'abbreviation' => 'RR', 'code' => 14, 'area_codes' => '[95]'],
            ['id' => 23, 'name' => 'Rio Grande do Sul', 'abbreviation' => 'RS', 'code' => 43, 'area_codes' => '[53,54,55,51]'],
            ['id' => 24, 'name' => 'Santa Catarina', 'abbreviation' => 'SC', 'code' => 42, 'area_codes' => '[47,48,49]'],
            ['id' => 25, 'name' => 'Sergipe', 'abbreviation' => 'SE', 'code' => 28, 'area_codes' => '[79]'],
            ['id' => 26, 'name' => 'São Paulo', 'abbreviation' => 'SP', 'code' => 35, 'area_codes' => '[11,12,13,14,15,16,17,18,19]'],
            ['id' => 27, 'name' => 'Tocantins', 'abbreviation' => 'TO', 'code' => 17, 'area_codes' => '[63]'],
        ];

        DB::table('states')->insert($states);
    }
}
