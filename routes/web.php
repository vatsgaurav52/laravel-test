<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\B2BDashboardController;
use App\Http\Controllers\B2CDashboardController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/purchase', function () {
    return view('purchase');
})->name('buy');

Route::post('/purchase', [ProductController::class, 'purchase'])->name('purchase');

Auth::routes();
/*------------------------------------------
All Admin Routes List
--------------------------------------------*/
Route::middleware(['auth', 'user-access:Admin'])->group(function () {
  
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('delete_subs', [B2CDashboardController::class, 'refundPayment'])->name('admin.refund');

});
  
/*------------------------------------------
All B2C Routes List
--------------------------------------------*/

Route::group(['prefix'=>'b2c','middleware' => ['auth', 'user-access:B2C Customer']], function () {
    Route::get('dashboard', [B2CDashboardController::class, 'index'])->name('B2C.dashboard');
    Route::post('delete_subs', [B2CDashboardController::class, 'refundPayment'])->name('B2C.refund');

});

/*------------------------------------------
All B2B Routes List
--------------------------------------------*/
Route::group(['prefix'=>'b2b','middleware' => ['auth', 'user-access:B2C Customer']], function () {
    Route::get('b2b/dashboard', [B2BDashboardController::class, 'index'])->name('B2B.dashboard');
    Route::post('delete_subs', [B2BDashboardController::class, 'refundPayment'])->name('B2B.refund');
});
