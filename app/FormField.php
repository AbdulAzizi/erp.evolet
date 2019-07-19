<?php

namespace App;

use Illuminate\Support\Facades\DB;

use App\Field;

class FormField extends Field
{
    protected $table = 'fields';

    /**
     * Override model's toArray() to add some new attributes
     */
    public function toArray()
    {
        $arrayField = parent::toArray();
        
        $listTypeFieldID = FieldType::where('name', 'list')->first()->id;

        if ($this->type_id === $listTypeFieldID) {
            $arrayField = $this->addListProps($arrayField);
        }

        $manyListTypeFieldID = FieldType::where('name', 'many-to-many-list')->first()->id;

        if ($this->type_id === $manyListTypeFieldID) {
            $arrayField = $this->addManyToManyListProps($arrayField);
        }

        return $arrayField;
    }

    /**
     * Helpers
     *
     */

    private function addListProps(array $arrayField)
    {
        $listTableName = DB::table('list_fields')->where('field_id', $this->id)->value('list_type');

        $listItems = DB::table($listTableName)->get()->toArray();

        $arrayField['items'] = $listItems;

        return $arrayField;
    }

    private function addManyToManyListProps(array $arrayField)
    {
        $arrayField = $this->addListProps($arrayField);

        $listTable = DB::table('list_fields')->where('field_id', $this->id)->first();

        $relatedListID = DB::table('many_to_many_list_fields') //Check from list field ids
            ->where('list_field_id', $listTable->id)
            ->value('foreign_list_field_id');

        if (!$relatedListID) { //If there isn't any, then check from foreign list field ids

            $relatedListID = DB::table('many_to_many_list_fields')
                ->where('foreign_list_field_id', $listTable->id)
                ->value('list_field_id');
        }

        $relatedListTable = DB::table('list_fields')->where('id', $relatedListID)->first();

        $arrayField['listName'] = $listTable->list_type;
        $arrayField['relatedListName'] = $relatedListTable->list_type;

        return $arrayField;
    }
}
