<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use League\Csv\Reader;

class LoadProductsPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $csv = Reader::createFromPath(storage_path('docs/product_groups.csv'));
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $productCategory = new \App\ProductCategory();
            $productCategory->name = $record[0];
            $productCategory->save();
        }
        $csv = Reader::createFromPath(storage_path('docs/products.csv'));
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $product = new \App\Product();
            $product->product_category_id = $record[0];
            $product->name = $record[1];
            $product->spend = $record[2];
            $product->details = $record[3];
            $product->save();
        }
        $csv = Reader::createFromPath(storage_path('docs/terms.csv'));
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $term = new \App\Term();
            $term->type = \App\Enums\TermType::getValue($record[1]);
            $term->length = $record[0];
            $term->save();

        }
        $csv = Reader::createFromPath(storage_path('docs/addons.csv'));
        $records = $csv->getRecords();
        $categoryIds = \App\ProductCategory::all()->pluck('id');
        foreach ($records as $record) {
            $addon = new \App\Addon();
            $addon->name = $record[2];
            $addon->save();
            if($record[0] == '0' && $record[1] == '0'){
                $addon->product_categories()->attach($categoryIds);
            }elseif($record[0] != '0' && $record[1] == '0'){
                $ids = explode(',', $record[0]);
                $addon->product_categories()->attach($ids);
            }elseif($record[0] == '0' && $record[1] == '1'){
                $ids = explode(',', $record[1]);
                $addon->products()->attach($ids);
            }
        }
        $csv = Reader::createFromPath(storage_path('docs/pricing.csv'));
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $price = new \App\Price();
            $price->quote = $record[0];
            $price->set_up_amount = $record[1];
            $price->recurring_amount = $record[2];
            $price->term_id = $record[5];
            $price->save();
            if($record[3] != '0'){
                $product = \App\Product::find($record[3]);
                $product->prices()->save($price);
            }
            if ($record[4] != '0'){
                $addon = \App\Addon::find($record[4]);
                $addon->prices()->save($price);
            }
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
