<?php

use App\Http\Controllers\HouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::get("/init", [HouseController::class, 'init']);
Route::post("/search", [HouseController::class, 'search']);
