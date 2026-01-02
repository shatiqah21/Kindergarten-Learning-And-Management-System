<?php

namespace Database\Seeders;

use App\Models\MyClass;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('subjects')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->createSubjects();
    }

    protected function createSubjects()
    {
        $subjects = [
            'English Language',
            'Mathematics',
            'Bahasa Melayu',
            'Sukan',
            'Bahasa Arab',
            'Science',
            'Hafazan Al-Quran',
            'Amali Solat',
        ];

        $sub_slug = [
            'Eng',
            'Math',
            'BM',
            'Sukan',
            'Arab',
            'Sci',
            'Hafazan',
            'Solat',
        ];

        // Get first teacher
        $teacher = User::where('user_type', 'teacher')->first();

        if (!$teacher) {
            $this->command->error('No teacher found! Please seed at least one teacher before running this seeder.');
            return;
        }

        $teacher_id = $teacher->id;
        $my_classes = MyClass::all();

        foreach ($my_classes as $my_class) {
            $data = [];

            for ($i = 0; $i < count($subjects); $i++) {
                $data[] = [
                    'name' => $subjects[$i],
                    'slug' => $sub_slug[$i],
                    'my_class_id' => $my_class->id,
                    'teacher_id' => $teacher_id,
                ];
            }

            DB::table('subjects')->insert($data);
        }
    }
}
