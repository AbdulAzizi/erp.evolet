<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $listsFromFiles = [
            'mnns_list' => '/lists/mnns.php',
            'drug_forms_list' => '/lists/drug_forms.php',
            'countries_list' => '/lists/countries.php',
            'stability_zone_list' => '/lists/stability_zone.php',
            'product_class_list' => '/lists/product_class.php',
            'age_gender_list' => '/lists/age_gender.php',
            'atx_list' => '/lists/atx.php',
            'gp_bu_list' => '/lists/gp_bu.php',
            'gp_stk_pk_list' => '/lists/gp_stk_pk.php',
            'pmt_list' => '/lists/pmt.php',
            'pnk1_list' => '/lists/pnk1.php',
            'pnk2_list' => '/lists/pnk2.php',
            'pnk4_list' => '/lists/pnk4.php',
            'drug_classification_list' => '/lists/drug_classification.php',
            'strani_poiska' => '/lists/strani_poiska.php'
        ];

        foreach ($listsFromFiles as $listName => $listFilePath) {
            $this->seedListFromFile($listFilePath, $listName);
        }
        
        $this->seedMnnForms();

    }

    private function seedMnnForms()
    {
        $mnnForms = $this->getListFormFile('/lists/form_mnns.php');

        $this->insertToPivotTable($mnnForms, 'mnns_list', 'drug_forms_list');
    }

    
    /**
     * Helpers
     *
     */
    
    private function seedListFromFile($filePath, $tableName)
    {
        $records = $this->getListFormFile($filePath);

        $this->seedPlainList($records, $tableName);
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

    private function insertToPivotTable(array $records, $firstListName, $secondListName, $firstListFieldName = "name", $secondListFieldName = "name")
    {
        foreach ($records as $record) {
            
            $firstListID = DB::table($firstListName)->where($firstListFieldName, $record[0])->value("id");
            $secondListID = DB::table($secondListName)->where($secondListFieldName, $record[1])->value("id");

            DB::table("list_relations")->insert(
                [
                    "list_type" => $firstListName,
                    "foreign_list_type" => $secondListName,
                    "list_id" => $firstListID,
                    "foreign_list_id" => $secondListID,
                ]
            );
        }
    }

    private function getListFormFile($filePath)
    {
        return require realpath(app_path() . $filePath);    
    }
}
