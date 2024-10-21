<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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
                'email' => 'admin.ilham@example.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'perusahaan' => 'FI Studio',
                'alamat' => 'Karang Ploso',
                'no_telp' => '081xxxxxxx1',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ilham MP',
                'email' => 'user.ilham@example.com',
                'password' => bcrypt('ilham123'),
                'role' => 'user',
                'perusahaan' => 'CV. Batu Permata',
                'alamat' => 'Pandaan',
                'no_telp' => '081xxxxxxx2',
            ],
            [
                'name' => 'Maulana',
                'email' => 'user.maulana@example.com',
                'password' => bcrypt('maulana123'),
                'role' => 'user',
                'perusahaan' => 'CV. Batagor',
                'alamat' => 'Kediri',
                'no_telp' => '081xxxxxxx3',
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
