<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListRelationsController extends Controller
{
    public function getRelatedData(Request $request)
    {
        $request->validate([
            'list' => 'required',
            'foreignList' => 'required',
            'listId' => 'required',
        ]);

        $listItemId = $request['listId'];
        $listName = $request['list'];
        $foreignListName = $request['foreignList'];

        $relatedIds = DB::table("list_relations")
            ->where("list_type", $listName)
            ->where("foreign_list_type", $foreignListName)
            ->where("list_id", $listItemId)                //Filter by list_id
            ->pluck("foreign_list_id")
            ->toArray();

        if (!$relatedIds) { //If there isn't anything by list<->foreign_list 

            //Then try foreign_list<->list
            
            $relatedIds = DB::table("list_relations")
                ->where("list_type", $foreignListName)
                ->where("foreign_list_type", $listName)
                ->where("foreign_list_id", $listItemId)  //Filter by foreign_list_id
                ->pluck("list_id")
                ->toArray();
        }

        $relatedData = DB::table($foreignListName)->whereIn("id", $relatedIds)->get();

        return $relatedData;
    }
}
