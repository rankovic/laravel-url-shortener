<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/{code}', RedirectController::class)
    ->where('code', '[A-Za-z0-9]{5,16}');
