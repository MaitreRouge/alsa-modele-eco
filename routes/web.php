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
    return redirect("/dashboard");
});

Route::get("/dashboard", [EntrepriseController::class, "showDashboard"]);

Route::get('/new', [EntrepriseController::class, "showCreate"]);
Route::post('/new', [EntrepriseController::class, "processCreate"]);

Route::prefix('/edit/{id}')->group(function () {
    Route::get('', function ($id) {;
        return redirect("/edit/$id/fiche");
    });

    Route::get("/fiche", [EntrepriseController::class, "showMainPage"]);
    Route::post("/fiche", [EntrepriseController::class, "processCreate"]);

    Route::get("/data", [EntrepriseController::class, "showDataPage"]);
    Route::get("/telephonie", [EntrepriseController::class, "showTelephoniePage"]);
    Route::get("/services", [EntrepriseController::class, "showServicesPage"]);
});

Route::get('/login', [UserController::class, "showLogin"]);

Route::post('/login', [UserController::class, "processLogin"]);

