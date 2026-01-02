<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Qs;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
    

        $this->createNewUsers();
        $this->createManyUsers( 1);
    }

    protected function createNewUsers()
    {
        $password = Hash::make('1234567'); // Default user password

        $d = [

            ['name' => 'Super Admin',
                'email' => 'suparadmin@gmail.com',
                'username' => 'superAdmin',
                'password' => $password,
                'user_type' => 'super_admin',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

            ['name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'user_type' => 'admin',
            'username' => 'admin',
            'code' => strtoupper(Str::random(10)),
            'remember_token' => Str::random(10),
            ],

            ['name' => 'Teacher Mira',
                'email' => 'teacherMira@gmail.com',
                'user_type' => 'teacher',
                'username' => 'teacher_Mira',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

             ['name' => 'Teacher Aiyisha',
                'email' => 'teacherAiyisha@gmail.com',
                'user_type' => 'teacher',
                'username' => 'teacher_Aiyisha',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

             ['name' => 'Teacher Nana',
                'email' => 'teacherNana@gmail.com',
                'user_type' => 'teacher',
                'username' => 'teacher_Nana',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

             ['name' => 'Teacher Lily',
                'email' => 'teacherLily@gmail.com',
                'user_type' => 'teacher',
                'username' => 'teacher_Lily',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

             ['name' => 'Teacher Bobby',
                'email' => 'teacherBobby@gmail.com',
                'user_type' => 'teacher',
                'username' => 'teacher_Bobby',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],


            ['name' => 'Arash Parents',
                'email' => 'arash@gmail.com',
                'user_type' => 'parent',
                'username' => 'parent_Arash',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],

            ['name' => 'Aiman Parents',
                'email' => 'aiman@gmail.com',
                'user_type' => 'parent',
                'username' => 'parent_Aiman',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],


            ['name' => 'Accountant',
                'email' => 'accountant@gmail.com',
                'user_type' => 'accountant',
                'username' => 'accountant',
                'password' => $password,
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
        ];
        DB::table('users')->insert($d);
    }

    protected function createManyUsers(int $count)
    {
        $data = [];
        $user_type = Qs::getAllUserTypes(['super_admin', 'librarian', 'student']);

        for($i = 1; $i <= $count; $i++){

            foreach ($user_type as $k => $ut){

                $data[] = ['name' => ucfirst($user_type[$k]).' '.$i,
                    'email' => $user_type[$k].$i.'@'.$user_type[$k].'.com',
                    'user_type' => $user_type[$k],
                    'username' => $user_type[$k].$i,
                    'password' => Hash::make($user_type[$k]),
                    'code' => strtoupper(Str::random(10)),
                    'remember_token' => Str::random(10),
                ];

            }

        }

        DB::table('users')->insert($data);
    }
}
