<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Lga;

class LgasTableSeeder extends Seeder
{
    public function run()
    {
        // Disable FK check for truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lgas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $now = Carbon::now();

        $lgas = [
            // Johor - state_id 33
            ['name' => 'Johor Bahru', 'state_id' => 1],
            ['name' => 'Batu Pahat', 'state_id' => 1],
            ['name' => 'Kluang', 'state_id' => 1],
            ['name' => 'Muar', 'state_id' => 1],
            ['name' => 'Segamat', 'state_id' => 1],
            ['name' => 'Pontian', 'state_id' => 1],
            ['name' => 'Kota Tinggi', 'state_id' => 1],
            ['name' => 'Mersing', 'state_id' => 1],

            // Kedah - state_id 2 
            ['name' => 'Alor Setar', 'state_id' => 2], 
            ['name' => 'Kuala Muda', 'state_id' => 2], 
            ['name' => 'Kubang Pasu', 'state_id' => 2], 
            ['name' => 'Kulim', 'state_id' => 2], 
            ['name' => 'Langkawi', 'state_id' => 2], 
            ['name' => 'Padang Terap', 'state_id' => 2], 
            ['name' => 'Pendang', 'state_id' => 2], 
            ['name' => 'Baling', 'state_id' => 2],
            ['name' => 'Yan', 'state_id' => 2],

            // Kelantan - state_id 35
            ['name' => 'Kota Bharu', 'state_id' => 3],
            ['name' => 'Pasir Mas', 'state_id' => 3],
            ['name' => 'Tumpat', 'state_id' => 3],
            ['name' => 'Machang', 'state_id' => 3],
            ['name' => 'Kuala Krai', 'state_id' => 3],
            ['name' => 'Pasir Puteh', 'state_id' => 3],
            ['name' => 'Bachok', 'state_id' => 3],
            ['name' => 'Gua Musang', 'state_id' => 3],
            ['name' => 'Jeli', 'state_id' => 3],

            // Melaka - state_id 36
            ['name' => 'Melaka Tengah', 'state_id' => 4],
            ['name' => 'Alor Gajah', 'state_id' => 4],
            ['name' => 'Jasin', 'state_id' => 4],

            // Negeri Sembilan - state_id 37
            ['name' => 'Seremban', 'state_id' => 5],
            ['name' => 'Port Dickson', 'state_id' => 5],
            ['name' => 'Jempol', 'state_id' => 5],
            ['name' => 'Kuala Pilah', 'state_id' => 5],
            ['name' => 'Rembau', 'state_id' => 5],
            ['name' => 'Tampin', 'state_id' => 5],
            ['name' => 'Jelebu', 'state_id' => 5],

            // Pahang - state_id 6
            ['name' => 'Kuantan', 'state_id' => 6],
            ['name' => 'Bentong', 'state_id' => 6],
            ['name' => 'Cameron Highlands', 'state_id' => 6],
            ['name' => 'Jerantut', 'state_id' =>6],
            ['name' => 'Lipis', 'state_id' =>6],
            ['name' => 'Maran', 'state_id' =>6],
            ['name' => 'Pekan', 'state_id' => 6],
            ['name' => 'Raub', 'state_id' => 6],
            ['name' => 'Rompin', 'state_id' => 6],
            ['name' => 'Temerloh', 'state_id' => 6],

           // Pulau Pinang - state_id 7
            ['name' => 'Seberang Perai Utara', 'state_id' => 7],
            ['name' => 'Seberang Perai Tengah', 'state_id' => 7],
            ['name' => 'Seberang Perai Selatan', 'state_id' => 7],
            ['name' => 'Timur Laut', 'state_id' => 7],
            ['name' => 'Barat Daya', 'state_id' => 7],


              // Perak - state_id 8
                ['name' => 'Ipoh', 'state_id' => 8],
                ['name' => 'Kuala Kangsar', 'state_id' => 8],
                ['name' => 'Larut, Matang dan Selama', 'state_id' => 8],
                ['name' => 'Hilir Perak', 'state_id' => 8],
                ['name' => 'Kerian', 'state_id' => 8],
                ['name' => 'Manjung', 'state_id' => 8],
                ['name' => 'Batang Padang', 'state_id' => 8],
                ['name' => 'Perak Tengah', 'state_id' => 8],
                ['name' => 'Kinta', 'state_id' => 8],

                // Perlis - state_id 9
                ['name' => 'Kangar', 'state_id' => 9],
                ['name' => 'Arau', 'state_id' => 9],
                ['name' => 'Padang Besar', 'state_id' => 9],

                // Putrajaya - state_id 10
                ['name' => 'Presint 1-20', 'state_id' => 10],

                // Sabah - state_id 11
                ['name' => 'Kota Kinabalu', 'state_id' => 11],
                ['name' => 'Sandakan', 'state_id' => 11],
                ['name' => 'Tawau', 'state_id' => 11],
                ['name' => 'Lahad Datu', 'state_id' => 11],
                ['name' => 'Keningau', 'state_id' => 11],
                ['name' => 'Beaufort', 'state_id' => 11],
                ['name' => 'Ranau', 'state_id' => 11],
                ['name' => 'Kudat', 'state_id' => 11],

                // Sarawak - state_id 12
                ['name' => 'Kuching', 'state_id' => 12],
                ['name' => 'Sibu', 'state_id' => 12],
                ['name' => 'Miri', 'state_id' => 12],
                ['name' => 'Bintulu', 'state_id' => 12],
                ['name' => 'Limbang', 'state_id' => 12],
                ['name' => 'Mukah', 'state_id' => 12],
                ['name' => 'Sri Aman', 'state_id' => 12],
                ['name' => 'Kapit', 'state_id' => 12],

                // Selangor - state_id 13
                ['name' => 'Petaling', 'state_id' => 13],
                ['name' => 'Gombak', 'state_id' => 13],
                ['name' => 'Hulu Langat', 'state_id' => 13],
                ['name' => 'Hulu Selangor', 'state_id' => 13],
                ['name' => 'Klang', 'state_id' => 13],
                ['name' => 'Kuala Langat', 'state_id' => 13],
                ['name' => 'Sabak Bernam', 'state_id' => 13],
                ['name' => 'Sepang', 'state_id' => 13],

                // Terengganu - state_id 14
                ['name' => 'Kuala Terengganu', 'state_id' => 14],
                ['name' => 'Marang', 'state_id' => 14],
                ['name' => 'Dungun', 'state_id' => 14],
                ['name' => 'Kemaman', 'state_id' => 14],
                ['name' => 'Besut', 'state_id' => 14],
                ['name' => 'Hulu Terengganu', 'state_id' => 14],
                ['name' => 'Setiu', 'state_id' => 14],

                // Kuala Lumpur - state_id 15
                ['name' => 'Cheras', 'state_id' => 15],
                ['name' => 'Setapak', 'state_id' => 15],
                ['name' => 'Kepong', 'state_id' => 15],
                ['name' => 'Lembah Pantai', 'state_id' => 15],
                ['name' => 'Titiwangsa', 'state_id' => 15],

                // Labuan - state_id 16
                ['name' => 'Labuan Town', 'state_id' => 16],
            ];
        

        foreach ($lgas as &$lga) {
            $lga['created_at'] = $now;
            $lga['updated_at'] = $now;
        }

        Lga::insert($lgas);
    }
}
