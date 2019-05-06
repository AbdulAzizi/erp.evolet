<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    public function mnns(Request $request){
        // $result = Marriage::where('name', 'LIKE', '%' . $email_or_name . '%')
        //                 ->orWhere('email', 'LIKE', '%' . $email_or_name . '%')
        //                 ->get();

        $keyword = $request->search;
        
        $mnns = \App\Mnn::where('name', 'LIKE', '%' . $keyword . '%')
                            ->get();
        return $mnns;
    }

    public function forms(Request $request)
    {
        $mnnId = $request->mnn_id;

        if ($mnnId) {
            $forms = \App\Mnn::find($mnnId)->forms;
            return $forms;
        }
        
        return response( "Mnn is net set", 404);

    }
}
