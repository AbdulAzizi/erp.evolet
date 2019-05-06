<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(TableSeeder::class);
        $this->call(DivisionTableSeeder::class);
        // $this->call(MnnSeeder::class);
        // $this->call(FormsSeeder::class);
        // $this->call(FormMnnSeeder::class);
    }
}
