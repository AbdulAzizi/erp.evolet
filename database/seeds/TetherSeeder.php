<?php

use Illuminate\Database\Seeder;
use App\Tether;

class TetherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Tether::class, 20)->create();
    }
}
