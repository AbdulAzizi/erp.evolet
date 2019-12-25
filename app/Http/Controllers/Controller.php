<?php

namespace App\Http\Controllers;

use App\History;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function alert($message){
        $alerts = session()->get('alerts');
        
        if( $alerts )
            $alerts[] = $message;
        else
            $alerts = [$message];
        
        session()->flash('alerts', $alerts);
    }

    protected function loadListFieldValues($listFields)
    {
        $listFieldIDs = $listFields->pluck('id');

        $listFieldTables = DB::table('list_fields')
            ->whereIn('field_id', $listFieldIDs)
            ->select('field_id', 'list_type')
            ->get()
            ->groupBy('list_type');

        foreach ($listFieldTables as $tableName => $listField) {

            $fieldsToEager = $listFields->whereIn('id', collect($listField)->pluck('field_id'));

            $toEagerFieldValues = $fieldsToEager->pluck('pivot');

            $values = DB::table($tableName)
                ->whereIn('id', $toEagerFieldValues->pluck('value'))
                ->get();

            foreach ($toEagerFieldValues as $fieldValue) {
                $realValue = $values->firstWhere('id', $fieldValue->value);
                $fieldValue->value = $realValue ? $realValue->name : null;
            }
        }
    }

    protected function getListFieldsFromProducts($products)
    {
        $listFields = [];

        foreach ($products as $product) {
            foreach ($product->fields as $field) {
                if ($field->type->name == "list" || $field->type->name == "many-to-many-list") {
                    $listFields[] = $field;
                }
            }
        }

        return collect($listFields);
    }
}
