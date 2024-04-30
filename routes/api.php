<?php

use App\Http\Controllers\Api\KuantazController;
use Illuminate\Support\Facades\Route;

Route::get('/information',[KuantazController::class,'index']);
