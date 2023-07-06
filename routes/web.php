<?php

use App\Http\Controllers\ChangelogController;
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
    return redirect("/login");
});

Route::get("/login", [UserController::class, "showLogin"])->middleware("guest");
Route::post("/login", [UserController::class, "processLogin"])->middleware("guest");
Route::get("/logout", [UserController::class, "processLogout"])->middleware("auth");
Route::get("/logout", [UserController::class, "processLogout"])->middleware("auth");

//EntrepriseController (devis)
Route::middleware(["slash", "validClientId", "auth"])->group(function () {

    Route::get("/dashboard", [EntrepriseController::class, "showDashboard"])->withoutMiddleware(["validClientId"]);
    Route::get('/new', [EntrepriseController::class, "showCreate"])->withoutMiddleware(["validClientId"]);
    Route::post('/new', [EntrepriseController::class, "processCreate"])->withoutMiddleware(["validClientId"]);
    Route::get("/delete/{id}", [EntrepriseController::class, "deleteAll"]);
    Route::get("/delete/{id}/devis", [EntrepriseController::class, "deleteAllDevis"]);

    Route::prefix('/edit/{id}/')->group(function () {
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

});

//PrestationController
Route::middleware(["auth"])->prefix("/prestations/")->group(function () {

    Route::get("", function () {
        return redirect("prestations/data");
    });

    Route::middleware(["auth:admin"])->group(function () {
        Route::get("{category}/{sub}/new", [PrestationsController::class, "showNew"]);
        Route::post("{category}/{sub}/new", [PrestationsController::class, "processNew"]);

        Route::get("edit/{prestation}", [PrestationsController::class, "showEdit"]);
        Route::post("edit/{prestation}", [PrestationsController::class, "processEdit"]);

        Route::get("bulk-edit/{tri}", [PrestationsController::class, "showBulkEdit"]);
        Route::post("bulk-edit/{tri}", [PrestationsController::class, "processBulkEdit"]);

        Route::post("massdelete", [PrestationsController::class, "processMassDelete"]);
        Route::get("delete/{id}", [PrestationsController::class, "processDelete"]);
    });

    Route::get("{category}", [PrestationsController::class, "showList"]);
    Route::post("{category}", [PrestationsController::class, "showList"]);

});

//UserController (-> admin)
Route::middleware(["auth:admin"])->prefix("/users")->group(function () {
    Route::get("", function () {
        return redirect("users/list");
    });
    Route::get("/list", [UserController::class, "showList"]);
    Route::get("/create", [UserController::class, "showCreate"]);
    Route::post("/create", [UserController::class, "processCreate"]);
});

Route::middleware(["auth"])->prefix("changelog")->group(function () {
    Route::get("/", [ChangelogController::class, "showMain"]);
    Route::get("/new", [ChangelogController::class, "showNew"])->middleware("auth:admin");
    Route::post("/new", [ChangelogController::class, "processNew"])->middleware("auth:admin");
});

