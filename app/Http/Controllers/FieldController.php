<?php

namespace App\Http\Controllers;

use App\Field;
use App\Product;
use App\ProductValue;
use Illuminate\Http\Request;
use App\Events\ProductEditEvent;


class FieldController extends Controller
{
    public function edit(Request $request)
    {
        $field = Field::find($request->id);
        $product = Product::find($request->productId);

        $productValue = ProductValue::where(['field_id' => $request->id, 'product_id' => $request->productId])->get();

        $productValue->first()->value = $request->value;

        $productValue->first()->save();

        event(new ProductEditEvent($product, $field->label));
        
        return $productValue->first();
    }
}
