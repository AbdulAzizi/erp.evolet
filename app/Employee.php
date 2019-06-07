<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $with = ['user', 'position', 'responsibility'];

    protected $fillable = [ 'user_id', 'position_id', 'responsibility_id', 'division_id' ];

    public $timestamps = false;

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function responsibility()
    {
        return $this->belongsTo('App\Responsibility');
    }

    public function givenTasks()
    {
        return $this->morphMany('App\Task', 'from');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task', 'responsible_id');
    }

    public function allTasks()
    {
        return collect([
            [
                'name' => 'Мои Задачи',
                'data' => $this->tasks->load(['responsible.user', 'from']),
            ],
            [
                'name' => 'Порученные',
                'data' => $this->givenTasks->load(['responsible.user', 'from']),
            ],
        ]);
    }

    /**
     * @param integer $id User's id
     * @return Builder
     */
    public static function whereUser($id)
    {
        return self::filterByUser($id);
    }

    /**
     * @param integer $id User's id
     * @return Employee
     */
    public static function byUser($id)
    {
        return self::filterByUser($id, true);
    }

    /**
     * @param integer $id User's id
     * @param boolean $withEmployee  If true, loads employee, otherwise returns query
     * @return Builder|Employee
     */
    private static function filterByUser($id, $withEmployee = false)
    {
        if ($withEmployee) {
            return self::where('user_id', $id)->first();
        }

        return self::where('user_id', $id);
    }
}
