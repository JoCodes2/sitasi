<?php

use App\Http\Controllers\CMS\DosenController;
use App\Http\Controllers\CMS\KepakaranController;
use App\Http\Controllers\CMS\TopikPenelitianController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return view('admin.user');
});

Route::get('/dosen', function () {
    return view('admin.dosen');
});

Route::get('/kepakaran', function () {
    return view('admin.kepakaran');
});

// route api
Route::prefix('sitasi')->group(function () {
    Route::prefix('topik')->controller(TopikPenelitianController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('dosen')->controller(DosenController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('kepakaran')->controller(KepakaranController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
});
