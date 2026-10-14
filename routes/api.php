<?php

use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

Route::post('/links', [LinkController::class, 'store']);
Route::get('/links/{link}', [LinkController::class, 'show']);
