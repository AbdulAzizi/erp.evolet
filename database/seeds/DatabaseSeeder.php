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

        
        $this->call(PollSeeder::class);
        $this->call(DivisionTableSeeder::class);
        $this->call(PositionTableSeeder::class);
        $this->call(ResponsibilitySeeder::class);
        $this->call(FieldsTypesSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(FormTableSeeder::class);
        $this->call(FieldTableSeeder::class);
        $this->call(ProcessTableSeeder::class);
        $this->call(PositionLevelTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(ProjectSeeder::class);
        // $this->call(TetherSeeder::class);
        $this->call(ListsSeeder::class);
        // $this->call(ProductSeeder::class);
        $this->call(ProjectParticipantTableSeeder::class);
        $this->call(ResumeTableSeeder::class);
        // $this->call(ChatSeeder::class);
        // $this->call(MessageTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        // $this->call(FactorySeeder::class);
        $this->call(ResponsibilityDescriptionSeeder::class);
        // $this->call(TaskTableSeeder::class);
    }
}
