<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InertiaTestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;

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

// Item関連リソースコントローラー
Route::resource('items', ItemController::class)
->middleware(['auth', 'verified']); // ['auth', 'verified']は、laravelBreezeの認証で使うやつ（/dashboardのログインルーティングを参考）

// Customer関連リソースコントローラー
Route::resource('customers', CustomerController::class)
->middleware(['auth', 'verified']);

// Purchase関連リソースコントローラー
Route::resource('purchases', PurchaseController::class)
->middleware(['auth', 'verified']);

// Inertiaの動きテスト
Route::get('/inertia-test', function () {
    return Inertia::render('InertiaTest');
   });

// コンポーネントの動きテスト
Route::get('/component-test', function () {
    return Inertia::render('ComponentTest');
   });

Route::get('/inertia/index', [InertiaTestController::class, 'index'])->name('inertia.index');
Route::get('/inertia/create', [InertiaTestController::class, 'create'])->name('inertia.create');
Route::post('/inertia', [InertiaTestController::class, 'store'])->name('inertia.store');
Route::get('/inertia/show/{id}', [InertiaTestController::class, 'show'])->name('inertia.show');
Route::delete('/inertia/{id}', [InertiaTestController::class, 'delete'])->name('inertia.delete');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
