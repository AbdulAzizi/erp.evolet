<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function all(){

        $statuses = Status::all();

        return $statuses;
    }
}
