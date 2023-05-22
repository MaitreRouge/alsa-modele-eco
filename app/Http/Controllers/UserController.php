<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function showLogin()
    {
        return view("login");
    }

    public function processLogin(Request $request)
    {
//        dump("wip");
        dd("wip");
    }
}
