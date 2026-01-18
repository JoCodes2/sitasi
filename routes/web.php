<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CMS\DosenController;
use App\Http\Controllers\CMS\KepakaranController;
use App\Http\Controllers\CMS\GelombangController;
use App\Http\Controllers\CMS\MahasiswaController;
use App\Http\Controllers\CMS\PengajuanController;
use App\Http\Controllers\CMS\TopikPenelitianController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function () {
    return view('admin.user');
});


// route registrasi and login
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');
Route::get('/registrasi', function () {
    return view('auth.registrasi');
})->name('registrasi');
Route::post('sitasi/mahasiswa/create', [MahasiswaController::class, 'createData']);
Route::post('sitasi/login', [LoginController::class, 'login']);

Route::middleware(['auth', 'web'])->group(function () {
    // route web
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::get('/gelombang', function () {
        return view('pages.gelombang');
    });
    Route::get('/mahasiswa', function () {
        return view('pages.mahasiswa');
    });
    Route::get('/dosen', function () {
        return view('admin.dosen');
    });

    Route::get('/kepakaran', function () {
        return view('admin.kepakaran');
    });
    Route::get('/pengajuan', function () {
        return view('pages.pengajuan');
    });
    Route::get('/judul', function () {
        return view('pages.pengajuan-mahasiswa');
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

        Route::prefix('gelombang')->controller(GelombangController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::post('/create', 'createData');
            Route::get('/get/{id}', 'getDataById');
            Route::post('/update/{id}', 'updateData');
            Route::delete('/delete/{id}', 'deleteData');
        });
        Route::prefix('mahasiswa')->controller(MahasiswaController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::get('/get/{id}', 'getDataById');
            Route::post('/update/{id}', 'updateData');
            Route::delete('/delete/{id}', 'deleteData');
        });
        Route::prefix('pengajuan')->controller(PengajuanController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::post('/create', 'createData');
            Route::get('/get/{id}', 'getDataById');
            Route::post('/update/{id}', 'updateData');
            Route::delete('/delete/{id}', 'deleteData');
        });
        Route::get('/sitasi/pengajuan/check', [PengajuanController::class, 'checkPengajuan']);
    });
    Route::post('sitasi/logout', [LoginController::class, 'logout']);
});
