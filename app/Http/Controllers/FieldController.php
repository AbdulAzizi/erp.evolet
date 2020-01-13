<?php

namespace App\Http\Controllers;

use App\Events\ProductEditEvent;
use App\Field;
use App\FieldType;
use App\File;
use App\Form;
use App\Product;
use App\ProductValue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

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

        if ($field->type->name !== 'string') {
            $table_name = DB::table('list_fields')
                ->where('field_id', $field->id)
                ->first()
                ->list_type;

            $item = DB::table($table_name)
                ->find($request->value);
            $productValue->value = $item->name;
        }

        return $productValue;
    }

    public function create(Request $request)
    {
        $list = [];
        $form = Form::find($request->formId);
        $data = $request->data;
        $fields = [];
        foreach ($data as $value) {

            if (count($value['listItems']) > 0) {
                $field = Field::create([
                    'label' => $value['label'],
                    'name' => $value['name'],
                    'type_id' => $value['type'],
                    'abbreviation' => $value['abbreviation'],
                ]);
                $fields[] = $field;

                $this->createListFile($value['name'], $value['listItems']);

                $list[$value['name']] = '../storage/app/' . $value['name'] . '.php';

                $this->createListTable($value['name'], $field);

                $form->fields()->attach($field, ['form_id' => $form->id, 'field_id' => $field->id, 'required' => $value['required'], 'multiple' => $value['isMultiple']]);

            } else {

                $field = Field::create([
                    'label' => $value['label'],
                    'name' => $value['name'],
                    'type_id' => $value['type'],
                    'abbreviation' => $value['abbreviation'],
                ]);

                $fields[] = $field;

                $form->fields()->attach($field, ['form_id' => $form->id, 'field_id' => $field->id, 'required' => 0]);
            }
        }

        foreach ($list as $listName => $path) {
            $this->seedListFromFile($path, $listName);
        }

        return $fields;
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

    public function getFieldTypes()
    {
        return FieldType::all();
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

    private function createListTable($table, $field)
    {
        Schema::create($table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('name');
        });

        DB::table('list_fields')->insertGetId(['field_id' => $field->id, 'list_type' => $table]);
    }

    private function createListFile($value, $array)
    {
        Storage::disk('local')->put($value . '.php', '<?php
                return ' . json_encode(collect($array)
                ->flatten()
                ->filter(function ($val) {
                    return $val !== null;
                })));

        Storage::append($value . '.php', ';');
    }

    private function seedPlainList(array $records, $tableName)
    {
        $sqlValues = '';

        foreach ($records as $record) {
            $sqlValues .= "('$record'),";
        }

        $sqlValues = trim($sqlValues, ',');

        DB::select("INSERT INTO $tableName (name) VALUES $sqlValues");
    }

    private function seedListFromFile($filePath, $tableName)
    {
        $records = $this->getListFormFile($filePath);

        $this->seedPlainList($records, $tableName);
    }

    private function getListFormFile($filePath)
    {
        return require realpath(app_path($filePath));
    }
}
