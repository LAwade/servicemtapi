<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'userteste@example.com')->first()) {
            User::factory()->create([
                'name' => 'Usuario Teste',
                'email' => 'userteste@example.com',
                'password' => Hash::make('12345678'),
            ]);
        }

        $this->call([
            CrudSeeder::class,
        ]);
    }
}

// Compare this snippet from database/seeders/CrudSeeder.php: