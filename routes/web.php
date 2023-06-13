<?php

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\PrestationsController;
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

Route::middleware(["slash", "validClientId"])->prefix('/edit/{id}/')->group(function () {
    //localhost/edit/1
    Route::get('', function ($id) {
        return redirect("/edit/$id/fiche");
    });

    Route::get("fiche", [EntrepriseController::class, "showMainPage"]);
    Route::post("fiche", [EntrepriseController::class, "processCreate"]);

    Route::prefix("{category}")->group(function () {
        //localhost/edit/1/data
        Route::get("", [EntrepriseController::class, "showCategoryPage"]);

        Route::get("/add", [EntrepriseController::class, "listPrestations"]);
        Route::post("/add", [EntrepriseController::class, "listPrestations"]);

        Route::get("/add/{prestation}", [EntrepriseController::class, "showAddPrestations"])
            ->whereNumber("prestation");
        Route::post("/add/{prestation}", [EntrepriseController::class, "processAddPrestations"])
            ->whereNumber("prestation");

        Route::middleware("validPrestation")->group(function () {
            Route::get("/edit/{prestation}", [EntrepriseController::class, "showEditPrestations"])
                ->whereNumber("prestation");
            Route::post("/edit/{prestation}", [EntrepriseController::class, "processEditPrestations"])
                ->whereNumber("prestation");

            Route::get("/delete/{prestation}", [EntrepriseController::class, "deletePrestations"])
                ->whereNumber("prestation");
        });

    })->whereIn("category", ["data", "telephonie", "services"]);
});

Route::get("/delete/{id}", [EntrepriseController::class, "deleteAll"])->middleware(["validClientId"]);
Route::get("/delete/{id}/devis", [EntrepriseController::class, "deleteAllDevis"])->middleware(["validClientId"]);

Route::prefix("/prestations/")->group(function () {

    Route::get("", function () {
        return redirect("prestations/data");
    });

    Route::get("{category}", [PrestationsController::class, "showList"]);
    Route::post("{category}", [PrestationsController::class, "showList"]);

    Route::get("{category}/{sub}/new", [PrestationsController::class, "showNew"]);
    Route::post("{category}/{sub}/new", [PrestationsController::class, "processNew"]);

    Route::get("edit/{prestation}", [PrestationsController::class, "showEdit"]);
    Route::post("edit/{prestation}", [PrestationsController::class, "processEdit"]);

    Route::get("bulk-edit/{parent}", [PrestationsController::class, "showBulkEdit"]);
    Route::post("bulk-edit/{parent}", [PrestationsController::class, "processBulkEdit"]);

});

Route::get('/login', [UserController::class, "showLogin"]);

Route::post('/login', [UserController::class, "processLogin"]);

