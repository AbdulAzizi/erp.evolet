<?php

use Illuminate\Database\Seeder;
use App\Responsibility;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Responsibility::truncate();
        factory(Responsibility::class, 20)->create();
    }
}
