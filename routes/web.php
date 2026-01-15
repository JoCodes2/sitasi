<?php

use App\Http\Controllers\CMS\GelombangController;
use App\Http\Controllers\CMS\MahasiswaController;
use App\Http\Controllers\CMS\TopikPenelitianController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return view('admin.user');
});


// route registrasi and login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/registrasi', function () {
    return view('auth.registrasi');
})->name('registrasi');
// route web
Route::get('/gelombang', function () {
    return view('pages.gelombang');
});
Route::get('/mahasiswa', function () {
    return view('pages.mahasiswa');
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
    Route::prefix('gelombang')->controller(GelombangController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
    Route::prefix('mahasiswa')->controller(MahasiswaController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
});
