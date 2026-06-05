<?php

namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class OnlineShoppingController extends Controller
{
    //

    public function index(){

        

        
        return view('client.index');
    }

    public function collection_page(){
        return view('client.collection_page');
    }

    public function product_page(){
        return view('client.product_page');
    }

    public function wishlist_page() 
    {
        return view('client.wishlist_page');

    }
}
