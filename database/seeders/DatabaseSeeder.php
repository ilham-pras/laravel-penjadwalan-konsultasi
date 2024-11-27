<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Profile;
use App\Models\JamOperasional;
use Illuminate\Database\Seeder;
use App\Models\DurasiKonsultasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users = [
            [
                'name' => 'Admin IMP',
                'email' => 'gorengpisang196@gmail.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }

        $profiles = [
            [
                'user_id' => '1',
                'perusahaan' => 'FI Studio',
                'alamat' => 'Karang Ploso',
                'no_telp' => '081xxxxxxx1',
                'jenis_kelamin' => 'Laki-Laki',
            ],
        ];
        foreach ($profiles as $profile) {
            Profile::create($profile);
        }

        $jams = [
            [
                'tanggal_mulai' => '2024-11-18',
                'tanggal_selesai' => '2024-12-08',
                'hari_mulai' => 'Senin',
                'hari_selesai' => 'Jumat',
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '17:00:00',
            ],
        ];
        foreach ($jams as $jam) {
            JamOperasional::create($jam);
        }

        $durasis = [
            [
                'konsultasi' => 'Akuntansi',
                'durasi' => '30',
            ],
            [
                'konsultasi' => 'Sistem Informasi',
                'durasi' => '60',
            ],
        ];
        foreach ($durasis as $durasi) {
            DurasiKonsultasi::create($durasi);
        }
    }


}
