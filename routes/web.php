<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome');
})->name('index');

Route::middleware(['auth:sanctum', 'verified'])->group(function (){
    Route::get('/dashboard', function () {
        sleep(2);
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');


    Route::get('user/profile', function () {
        abort(404);
    });
    Route::get('dashboard/user/profile', function () {
        return Inertia::render('Profile/Show');
    })->name('profile.show');

});


Route::prefix("/Post")->group(function () {
    Route::get('/', [\App\Http\Controllers\PostController::class, 'index'])->name('Post.index');
    Route::post('/{id}', [\App\Http\Controllers\PostController::class, 'postPreview'])->name('Post.postPreview');

});




//require_once __DIR__.'/fortify.php';
