<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'santiago9746',
            'nombre' => 'Santiago',
            'primerApellido' => 'Cossio',
            'segundoApellido' => 'Prada',
            'email' => 'scossioprada04@gmail.com',
            'password' => bcrypt('12345678')
        ]);
    }
}
