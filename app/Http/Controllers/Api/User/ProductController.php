<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ProductsCollection;
use App\Models\Product;

class ProductController extends Controller
{


    public function list() {

        $products = Product::paginate(10);
        
        return response()->json([
            'status' => true,
            'data' => ProductsCollection::collection($products)->response()->getData(true)
        ] , 200);

    } 
}
