<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Pest\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        $user = User::create([
            'role_id' => 3,
            'name' => 'Doga Korkmaz',
            'nickname'  => 'dogakorkmaz09',
            'email' => 'admin@gmail.com',
            'avatar_source' => User::AVATAR_FILE_PATHS['nogender'],
            'biography' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores eius deleniti vel magni commodi delectus quasi, iure placeat mollitia accusamus animi molestias, ad id ipsa veritatis ipsum pariatur veniam modi.',
            'password' => bcrypt('123'),
            'remember_token' => Str::random(10),
        ]);

    }
}
