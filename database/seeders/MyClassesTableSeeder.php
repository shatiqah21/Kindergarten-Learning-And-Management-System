<?php

namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MyClassesTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete instead of truncate
        DB::table('my_classes')->delete();
        DB::statement('ALTER TABLE my_classes AUTO_INCREMENT = 1;');

        // Enable back foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get class type IDs
        $akademikId = ClassType::where('name', 'Akademik')->value('id');
        $transitId = ClassType::where('name', 'Transit')->value('id');
        $daycareId  = ClassType::where('name', 'Daycare')->value('id');
        
        
        $data = [
            // Akademik
            ['name' => '4 Ibnu Sina', 'class_type_id' => $akademikId],
           
            ['name' => '5 Usman', 'class_type_id' => $akademikId],
           
            ['name' => '6 Abu Bakar', 'class_type_id' => $akademikId],
           

            // Transit
            ['name' => 'Transit', 'class_type_id' => $transitId],
            
            
              // Daycare Classes
            ['name' => 'Daycare 1', 'class_type_id' => $daycareId],
       
       
        ];


        DB::table('my_classes')->insert($data);
    }
}
