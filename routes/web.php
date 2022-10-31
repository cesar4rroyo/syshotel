<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ConceptsController;
use App\Http\Controllers\Admin\FloorsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RoomsController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\UnitsController;
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
    Route::get('branch/delete/{id}/{listagain}', [BranchController::class, 'delete'])->name('branch.delete');
    Route::post('branch/uploadPhoto', [BranchController::class, 'uploadPhoto'])->name('branch.uploadPhoto');
    Route::get('branch/maintenance/{id}/{action}/{businessId}', [BranchController::class, 'maintenance'])->name('branch.maintenance');
    Route::resource('branch', BranchController::class)->except(['show']);
    /* floor routes */
    Route::post('floor/search', [FloorsController::class, 'search'])->name('floor.search');
    Route::get('floor/delete/{id}/{listagain}', [FloorsController::class, 'delete'])->name('floor.delete');
    Route::resource('floor', FloorsController::class)->except(['show']);
    /* room routes */
    Route::post('room/search', [RoomsController::class, 'search'])->name('room.search');
    Route::get('room/delete/{id}/{listagain}', [RoomsController::class, 'delete'])->name('room.delete');
    Route::resource('room', RoomsController::class)->except(['show']);
    /* room types */
    Route::post('roomtype/search', [RoomTypeController::class, 'search'])->name('roomtype.search');
    Route::get('roomtype/delete/{id}/{listagain}', [RoomTypeController::class, 'delete'])->name('roomtype.delete');
    Route::resource('roomtype', RoomTypeController::class)->except(['show']);
    /* services routes */
    Route::post('service/search', [ServicesController::class, 'search'])->name('service.search');
    Route::get('service/delete/{id}/{listagain}', [ServicesController::class, 'delete'])->name('service.delete');
    Route::resource('service', ServicesController::class)->except(['show']);
    /* products routes */
    Route::post('product/search', [ProductsController::class, 'search'])->name('product.search');
    Route::get('product/delete/{id}/{listagain}', [ProductsController::class, 'delete'])->name('product.delete');
    Route::resource('product', ProductsController::class)->except(['show']);
    /* categories routes */
    Route::post('category/search', [CategoriesController::class, 'search'])->name('category.search');
    Route::get('category/delete/{id}/{listagain}', [CategoriesController::class, 'delete'])->name('category.delete');
    Route::resource('category', CategoriesController::class)->except(['show']);
    /* concepts routes */
    Route::post('concept/search', [ConceptsController::class, 'search'])->name('concept.search');
    Route::get('concept/delete/{id}/{listagain}', [ConceptsController::class, 'delete'])->name('concept.delete');
    Route::resource('concept', ConceptsController::class)->except(['show']);
    /* units routes */
    Route::post('unit/search', [UnitsController::class, 'search'])->name('unit.search');
    Route::get('unit/delete/{id}/{listagain}', [UnitsController::class, 'delete'])->name('unit.delete');
    Route::resource('unit', UnitsController::class)->except(['show']);
});
