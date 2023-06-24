<?php

use App\Http\Controllers\JobSeekerController;
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
    return redirect()->route('fill_up_form');
});

Route::get('/fill_up_form', [JobSeekerController::class, 'index'])->name('fill_up_form');
Route::post('/submit_form', [JobSeekerController::class, 'store'])->name('submit_form');
