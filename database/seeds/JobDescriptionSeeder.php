<?php

use Illuminate\Database\Seeder;
use App\JobDescription;

class JobDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobDescription::truncate();
        factory(JobDescription::class, 20)->create();
    }
}
