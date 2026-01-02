<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassTypesTableSeeder extends Seeder
{
   public function run()
{
    // Safe method that avoids FK constraint error
    DB::table('class_types')->delete();

      $data = [
        ['name' => 'Akademik', 'code' => 'AKD'],
        ['name' => 'Daycare',  'code' => 'DYC'],
        ['name' => 'Transit',  'code' => 'TRN'],
    ];

    DB::table('class_types')->insert($data);
}
}
