<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ExampleController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // You can use this controller as a base for others controllers

    public function ping(Request $request){
        return "pong";
    }
}
