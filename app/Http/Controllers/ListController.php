<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    public function items( $id )
    {
        $table_name =  DB::table('list_fields')
                            ->where('field_id', $id)
                            ->first()
                            ->list_type;

        $items = DB::table($table_name)
                    ->get();

        return $items;
    }
}
