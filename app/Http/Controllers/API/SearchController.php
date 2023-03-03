<?php

namespace App\Http\Controllers\API;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $error = ['error' => 'No results found, please try with different keywords.'];
        if($request->has('q')){
            $clients = Client::search($request->get('q'))->get();
            return $clients->count() ? $clients : $error;
        }
        return $error;
    }
}
