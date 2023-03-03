<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Price;
use App\Term;
use App\Enums\TermType;
use Symfony\Component\Translation\Dumper\PoFileDumper;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['product_category', 'prices.term'])->get();
        foreach ($products as $product) {
            $product->category = $product->product_category->name;
            foreach ($product->prices as $price) {
                $typeId = $price->term->type;
               $price->term->type_name = TermType::getDescription($typeId);
            }
        };
        return response()->json($products, 200);
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->input('name');
        $product->details = $request->input('details');
        $product->spend = $request->input('spend') == 'true' ? true:false;
        $product->product_category_id = $request->input('category');
        $product->save();
        foreach ($request->input('prices') as $reqPrice) {
            $price = new Price();
            $term = $reqPrice['term_length'];
            if(TermType::getDescription($reqPrice['term_type']) != 'Monthly'){
                $term = 0;
            }
            $term = Term::firstOrCreate(['length' => $term, 'type' => $reqPrice['term_type']]);
            if($reqPrice['quote']){
                $price->set_up_amount = 0;
                $price->recurring_amount = 0;
                $price->quote = true;
            }else {
                $price->set_up_amount = $reqPrice['set_up_amount'];
                $price->recurring_amount = $reqPrice['recurring_amount'];
                $price->quote = false;
            }
            $price->term_id = $term->id;
            $price->save();
            $product->prices()->save($price);
        }
    }

    public function update(Product $product,Request $request)
    {
        $product->update(  ['name' => $request->input('name'),
        'details' => $request->input('details'),
        'spend' => $request->input('spend') == 'true' ? true:false, 'product_category_id' => $request->input('category') ]);

        foreach ($request->input('prices') as $reqPrice) {
            $term = $reqPrice['term_length'];
            if(TermType::getDescription($reqPrice['term_type']) != 'Monthly'){
                $term = 0;
            }
            $term = Term::firstOrCreate(['length' => $term, 'type' => $reqPrice['term_type']]);
            if($reqPrice['id']){
                $price = Price::find($reqPrice['id']);
                if($reqPrice['quote']){
                    $price->update(['set_up_amount' > 0,
                    'recurring_amount' > 0,
                    'quote' => true]);
                    }else{
                    $price->update([  'set_up_amount' => $reqPrice['set_up_amount'],
                    'recurring_amount' => $reqPrice['recurring_amount'],
                    'quote' => false]);
                }
            }else {
                $price = new Price();
                if ($reqPrice['quote']) {
                    $price->set_up_amount = 0;
                    $price->recurring_amount = 0;
                    $price->quote = true;
                } else {
                    $price->set_up_amount = $reqPrice['set_up_amount'];
                    $price->recurring_amount = $reqPrice['recurring_amount'];
                    $price->quote = false;
                }
            }
            $price->term_id = $term->id;
            $price->save();
            $product->prices()->save($price);
        }
        return response()->json('', 200);
    }

    public function edit(Product $product)
    {
        $product = Product::with(['prices.term', 'product_category'])->where('id', $product->id)->first();
        $product->category = $product->product_category->id;
        foreach ($product->prices as $price) {

            $price->term_length = $price->term->length;
            $price->term_type = $price->term->type;
        }
        return response()->json($product, 200);
    }

}
