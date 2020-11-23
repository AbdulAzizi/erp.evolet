<?php

namespace App;

use App\Notifications\SetupPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'password', 'position_level_id', 'division_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $with = ['positionLevel', 'positions'];

    protected $appends = ['fullname'];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SetupPassword($token));
    }

    public function division()
    {
        return $this->belongsTo('App\Division');
        
    }

    public function positionLevel()
    {
        return $this->belongsTo('App\PositionLevel');
    }

    public function positions()
    {
        return $this->belongsToMany('App\Position');
    }

    public function givenTasks()
    {
        return $this->morphMany('App\Task', 'from');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'responsible_id');
    }

    public function pcs()
    {
        return $this->belongsToMany('App\Division', 'managers', 'manager_id', 'pc_id')->as('manager')->withPivot('pc_id', 'country_id')->using('App\Manager');
    }

    public function allTasks()
    {
        return collect([
            [
                'name' => 'Мои Задачи',
                'data' => $this->tasks->load(['responsible', 'from']),
            ],
            [
                'name' => 'Порученные',
                'data' => $this->givenTasks->load(['responsible', 'from']),
            ],
        ]);
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'project_participants', 'participant_id', 'project_id')
            ->using('App\ProjectParticipant')
            ->withPivot([
                'role_id',
            ]);
    }

    public function projectParticipant()
    {
        return $this->hasMany('App\ProjectParticipant', 'participant_id', 'id');
    }

    /**
     * Removes all relation dependencies from User model
     *
     * @return Illuminate\Database\Eloquent\Builder
     */

    public static function alone()
    {
        return self::without(['positionLevel', 'positions']);
    }

    /**
     * Concatinates name and surname
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "$this->name $this->surname";
    }

    public function resume()
    {
        return $this->belongsToMany('App\Resume');
    }

    public function questionTask()
    {
        return $this->belongsToMany('App\QuestionTask','question_answers','user_id','question_task_id');
    }

    public function chats()
    {
        return $this->belongsToMany('App\Chat');
    }

    public function responsibilities()
    {
        return $this->belongsToMany('App\Responsibility');
    }
    
    public function entries()
    {
        return $this->hasMany('App\Entry');
    }

    public function facilities()
    {
        return $this->hasMany('App\Facility');
    }

    public function requests()
    {
        return $this->hasMany('App\Request');
    }
}
