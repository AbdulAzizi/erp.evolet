<?php

namespace App\Filters;

use App\Division;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TimesetFilters extends QueryFilters
{
    protected $request;
    static $users;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function start_time($term)
    {
        return $this->builder->whereDate('start_time', '>=', $term);
    }

    public function end_time($term)
    {
        return $this->builder->whereDate('end_time', '<=', $term);
    }

    public function division_id($term)
    {
        $divisionsIDs = Division::descendantsAndSelf($term)->pluck('id');
        self::$users = User::alone()
            ->whereIn('division_id', $divisionsIDs)
            ->get();

        $userIDs = self::$users->pluck('id');

        return $this->builder->whereHas('task', function (Builder $task) use ($userIDs) {
            $task->whereIn('responsible_id', $userIDs);
        });
    }

    public function user_id($term)
    {
        self::$users = User::alone()->find($term);

        return $this->builder->whereHas('task', function (Builder $task) use ($term) {
            $task->where('responsible_id', $term);
        });
    }

    public function date($term)
    {
        $date = new Carbon($term);
        $startOfWeek = $date->startOfWeek();
        
        $date = new Carbon($term);
        $endOfWeek = $date->endOfWeek();
        
        return $this->builder
            ->whereDate('start_time', '>=', $startOfWeek)
            ->whereDate('end_time', '<=', $endOfWeek);
    }

    public static function getUsers()
    {
        return self::$users;
    }
}
