<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
use App\Project;
use App\User;

class ProjectParticipantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();

        $kuratorUsers = User::whereHas('responsibilities',function (Builder $query){
                        $query->where('name','Куратор Портфель ПК');
                    })->get();
        $noUsers = User::whereHas('responsibilities',function (Builder $query){
                        $query->where('name','НО');
                    })->get();
        $pcUsers = User::whereHas('responsibilities',function (Builder $query){
                        $query->where('name','ПК');
                    })->get();

        foreach ($projects as $project) {
            $project->participants()->attach([
                getRandomId($kuratorUsers ) => ['role_id'=>4],
                getRandomId($noUsers ) => ['role_id'=>5],
                getRandomId($pcUsers ) => ['role_id'=>6],
            ]);
        }
    }
}
