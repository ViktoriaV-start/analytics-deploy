<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert($this->getTypes());
    }

    private function getTypes() {
        $data = [];
        foreach (config('global.token_types') as $value) {
            $data[] = [
                'type' => $value
            ];
        }
        return $data;
    }
}
