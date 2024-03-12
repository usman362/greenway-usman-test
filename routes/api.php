<?php

use App\Http\Controllers\Contact\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('contacts',[ContactController::class,'index'])->name('contacts.index');
Route::post('contacts',[ContactController::class,'store'])->name('contacts.store');
Route::get('contacts/{id}/edit',[ContactController::class,'edit'])->name('contacts.edit');
Route::post('contacts/{id}',[ContactController::class,'update'])->name('contacts.update');
Route::delete('contacts/{id}',[ContactController::class,'destroy'])->name('contacts.destroy');

