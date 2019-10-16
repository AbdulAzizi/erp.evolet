<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
use App\Project;
use App\Responsibility;
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

        // $kuratorUsers = User::whereHas('responsibilities',function (Builder $query){
        //                 $query->where('name','Куратор Портфел ПК стран');
        //             })->get();
        $kuratorUser = User::where([
                        'name' => 'Alisher',
                        'surname' => 'Baratov'
                    ])->first();
        // $noUsers = User::whereHas('responsibilities',function (Builder $query){
        //                 $query->where('name','НО');
        //             })->get();
        $noUser = User::where([
                            'name' => 'Mehroj',
                            'surname' => 'Khakimov'
                        ])->first();
        // $pcUsers = User::whereHas('responsibilities',function (Builder $query){
        //                 $query->where('name','ПК');
        //             })->get();
        $pcUser = User::where([
                            'name' => 'Azimjon',
                            'surname' => 'Vohidi'
                        ])->first();
        $rukNAP = User::whereHas('responsibilities',function (Builder $query){
                        $query->where('name','Рук НАП');
                    })->first();
        $RVZ = User::where([
                'name' => 'Сайёра',
                'surname' => 'Мирзоева'
            ])->first();

        foreach ($projects as $project) {
            $project->participants()->attach([
                $kuratorUser->id => ['role_id'=>4],
                $noUser->id => ['role_id'=>5],
                $pcUser->id => ['role_id'=>6],
                $rukNAP->id => [ 'role_id' => Responsibility::where('name','Рук НАП')->first()->id ],
                $RVZ->id => [ 'role_id' => Responsibility::where('name','РВЗ')->first()->id ],
            ]);
        }
    }
}
