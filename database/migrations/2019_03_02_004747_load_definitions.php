<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use League\Csv\Reader;
use App\FieldGroup;
use App\Definition;
use App\ProductCategory;

class LoadDefinitions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $csv = Reader::createFromPath(storage_path('docs/definitions.csv'));
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $fieldGroupId = 0;
            if($record[1] !== '') {
                $fieldGroup = FieldGroup::firstOrCreate(['name' => $record[1]]);
                $fieldGroupId = $fieldGroup->id;
            }
            $definition = Definition::create(['name' => $record[2], 'field_type' => $record[3], 'field_group_id' => $fieldGroupId, 'type' => 'product_category']);
            $categoryIDs = explode(',',$record[0]);
            $definition->product_categories()->sync($categoryIDs);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
