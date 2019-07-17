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
        $this->call(ProcessTableSeeder::class);
        $this->call(ResponsibilityTableSeeder::class);
        $this->call(FieldsTypesSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(TableSeeder::class);
        $this->call(DivisionTableSeeder::class);
        $this->call(PositionTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProjectSeeder::class);
        // $this->call(MnnSeeder::class);
        // $this->call(FormsSeeder::class);
        // $this->call(FormMnnSeeder::class);

    }
}
