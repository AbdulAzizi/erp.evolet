<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Builder;
use App\Project;
use App\Position;
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

        // $kuratorUsers = User::whereHas('positions',function (Builder $query){
        //                 $query->where('name','Куратор Портфел ПК стран');
        //             })->get();
        $kuratorUser = User::where([
                        'name' => 'Саидчон',
                        'surname' => 'Комилов'
                    ])->first();
        // $noUsers = User::whereHas('positions',function (Builder $query){
        //                 $query->where('name','НО');
        //             })->get();
        $noUser = User::where([
                            'name' => 'Парвина',
                            'surname' => 'Мирахмедова'
                        ])->first();
        // $pcUsers = User::whereHas('positions',function (Builder $query){
        //                 $query->where('name','ПК');
        //             })->get();
        $pcUser = User::where([
                            'name' => 'Абдулазиз',
                            'surname' => 'Нуров'
                        ])->first();
        $rukNAP = User::whereHas('positions',function (Builder $query){
                        $query->where('name','Рук НАП');
                    })->first();
        $RVZ = User::where([
                'name' => 'Сайёра',
                'surname' => 'Мирзоева'
            ])->first();

        foreach ($projects as $project) {
            $project->participants()->attach([
                $kuratorUser->id => ['role_id'=> Position::where('name','Куратор Промо кампания при Головном офисе (КПГ)')->first()->id ],
                $noUser->id => ['role_id'=> Position::where('name','Научный аналитик')->first()->id ],
                $pcUser->id => ['role_id'=> Position::where('name','ПК')->first()->id],
                $rukNAP->id => [ 'role_id' => Position::where('name','Рук НАП')->first()->id ],
                $RVZ->id => [ 'role_id' => Position::where('name','РВЗ')->first()->id ],
            ]);
        }
    }
}
