<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

App::setLocale("fr");

Route::get('/', function () {
    return view('welcome');
});


Route::get('/new', [EntrepriseController::class, "showCreate"]);
Route::post('/new', [EntrepriseController::class, "processCreate"]);
Route::get('/edit/{id}', [EntrepriseController::class, "showMainPage"]);

Route::get('/login', [UserController::class, "showLogin"]);

Route::post('/login', [UserController::class, "processLogin"]);
