<?php

namespace Database\Seeders;

use App\Models\MyClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('sections')->delete();
        DB::statement('ALTER TABLE sections AUTO_INCREMENT = 1;');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $c = MyClass::pluck('id')->all();

        // Optional: Prevent error if less than 4 classes
        if (count($c) < 4) {
            dd('Not enough classes in "my_classes" to seed sections. Please seed classes first.', $c);
        }

        $data = [
            ['name' => 'A', 'my_class_id' => $c[0], 'active' => 1],
            ['name' => 'B', 'my_class_id' => $c[1], 'active' => 1],
            ['name' => 'C', 'my_class_id' => $c[2], 'active' => 1],
            ['name' => 'TAM', 'my_class_id' => $c[3], 'active' => 1],
            ['name' => 'PAM', 'my_class_id' => $c[3], 'active' => 1],
        ];

        DB::table('sections')->insert($data);
    }
}
