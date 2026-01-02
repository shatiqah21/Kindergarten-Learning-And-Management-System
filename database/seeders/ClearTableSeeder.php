<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all necessary tables
        DB::table('student_records')->truncate();
        DB::table('users')->truncate();
        DB::table('user_types')->truncate();
        DB::table('skills')->truncate();
        DB::table('subjects')->truncate();
        DB::table('settings')->truncate();
        DB::table('sections')->truncate();
        DB::table('states')->truncate();
        DB::table('lgas')->truncate();
        DB::table('grades')->truncate();
        DB::table('dorms')->truncate();
        DB::table('blood_groups')->truncate();
        DB::table('my_classes')->truncate();
        DB::table('class_types')->truncate();
        DB::table('nationalities')->truncate();

        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
