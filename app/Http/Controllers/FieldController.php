<?php

namespace App\Http\Controllers;

use App\Field;
use App\Product;
use App\ProductValue;
use Illuminate\Http\Request;
use App\Events\ProductEditEvent;
use App\File;
use Illuminate\Support\Facades\DB;

class FieldController extends Controller
{
    public function edit(Request $request)
    {
        $field = Field::find($request->id);
        
        $product = Product::find($request->productId);

        $productValue = ProductValue::where(['field_id' => $request->id, 'product_id' => $request->productId])->first();

        $productValue->value = $request->value;

        $productValue->save();

        event(new ProductEditEvent($product, $field->label));

        if($field->type->name !== 'string'){
            $table_name =  DB::table('list_fields')
                                ->where('field_id', $field->id)
                                ->first()
                                ->list_type;
    
            $item = DB::table($table_name)
                        ->find($request->value);
            $productValue->value = $item->name;            
        }
        
        return $productValue;
    }

    public function getFieldsList(Request $request)
    {
        $product = Product::with('fieldsWithLists')->find($request->id);

        return $product->fieldsWithLists->where('id', $request->listId)->first();
    }

    public function getFields()
    {
        $fields = Field::all();

        return $fields;
    }

    public function getOnlyNotExistingFields(Request $request)
    {
        $file = File::find($request->id)->fields;

        $data = [];

        foreach ($file as $value) {

            $data[] = $value->id;
        }

        $fields = Field::whereNotIn('id', $data)->get();

        return $fields;


    }
}
