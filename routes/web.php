<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BusinessController;
use Illuminate\Support\Facades\Route;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('business/search', [BusinessController::class, 'search'])->name('business.search');
    Route::get('business/delete/{id}/{listagain}', [BusinessController::class, 'delete'])->name('business.delete');
    Route::resource('business', BusinessController::class)->except(['show']);

    Route::post('branch/search', [BranchController::class, 'search'])->name('branch.search');
    Route::get('branch/maintenance/{id}/{action}', [BranchController::class, 'maintenance'])->name('branch.maintenance');
    Route::get('branch/delete/{id}/{listagain}', [BranchController::class, 'delete'])->name('branch.delete');
    Route::resource('branch', BranchController::class)->except(['show']);
});