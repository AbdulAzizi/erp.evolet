<?php

namespace App\Http\Controllers;

use App\FieldType;
use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    public function index()
    {
        $fieldTypes = FieldType::all();

        return $fieldTypes;
    }
}
