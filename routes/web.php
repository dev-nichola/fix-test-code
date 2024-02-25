<?php

use App\Http\Controllers\Apps\DashboardController;
use App\Http\Controllers\Apps\ProductController;
use App\Http\Controllers\Apps\ProjectSettingController;
use App\Http\Controllers\Apps\RoleController;
use App\Http\Controllers\Apps\TransactionController;
use App\Http\Controllers\Apps\TransactionDetailController;
use App\Http\Controllers\Apps\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\SessionToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

// Start Authentication
Route::get("/login", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "store"])->name("login.store");

Route::get("/logout", function () {
  Auth::logout();
  Alert::success("Sukses!", "Berhasil logout!");
  SessionToken::query()->where("session_token", session("session_token"))->update(["is_login" => 0]);

  return redirect()->route("login");
});
// End Authentication

Route::middleware(["auth", "check_maintanance", "check_session_token"])->group(function () {
  Route::get("/app/dashboard", DashboardController::class)->name("app.dashboard")->middleware("check_authorized:002D");

  Route::post("/app/users/get", [UserController::class, "get"])->name("app.users.get")->middleware("check_authorized:003U");
  Route::resource("/app/users", UserController::class, ["as" => "app"])->middleware("check_authorized:003U|004R");

  Route::resource("/app/roles", RoleController::class, ["as" => "app"])->middleware("check_authorized:004R");
  Route::post("/app/roles/{role}/assign-user", [RoleController::class, "assign_user"])->name("app.roles.assign_user")->middleware("check_authorized:004R");

  Route::get("/app/settings", [ProjectSettingController::class, "index"])->name("app.settings.index")->middleware("check_authorized:005S");
  Route::put("/app/settings", [ProjectSettingController::class, "update"])->name("app.settings.update")->middleware("check_authorized:005S");

  Route::prefix('app')->name('app.')->controller(ProductController::class)->middleware('check_authorized:006P')->group(function () {
    Route::post("product/get", 'getProducts');
    Route::get("product", 'index')->name('product');
    Route::get("product/create", 'create')->name('product.create');
    Route::post("product/create", 'store')->name('product.store');
    Route::get('product/show/{id}', 'show')->name('product.show');
    Route::get('product/edit/{id}', 'edit')->name('product.edit');
    Route::put('product/edit/{id}', 'update')->name('product.update');
    Route::delete('product/destroy/{id}',  'destroy')->name('product.destroy');
  });

  Route::prefix('app')->controller(TransactionController::class)->name('app.')->group(function () {
    Route::post('sales/get', 'getTransaction');
    Route::get('sales', 'index')->name('sales.index');
    Route::get('transaction/new', 'create')->name('transaction.new') ;
    Route::post('sales/create', 'store')->name('sales.create');
    Route::get('sales/show/{id}', 'show')->name('sales.show');
    Route::delete('sales/destroy/{id}', 'destroy')->name('sales.destroy');

  });

  Route::prefix('app')->controller(TransactionDetailController::class)->name('app.')->group(function() {
    Route::post('transaction/product', 'getProducts');
    Route::get('transaction', 'index')->name('transaction.create');
    Route::post('transaction', 'store')->name('transaction.store');
    Route::delete('transaction/{productId}', 'destroy')->name('transaction.delete');
  });

});

Route::get("/maintenance", function () {
  auth()->logout();
  return view("admin.maintenance");
})->name("maintenance")->middleware("check_maintanance");
