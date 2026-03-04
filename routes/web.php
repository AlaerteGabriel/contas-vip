<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CarteiraController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

Route::prefix('/')->group(function(){

    //Redirecionamos o acesso principal para a rota de login.
    Route::redirect('', '/login')->name('admin.login');

    //rotas para redefinição de senha
    Route::get('redefinir-senha', [PasswordController::class, 'linkCreate'])->name('admin.password.request');
    Route::post('link-senha', [PasswordController::class, 'linkStore'])->middleware(ProtectAgainstSpam::class)->name('admin.password.email');

    Route::get('nova-senha/{token}', [PasswordController::class, 'create'])->name('password.reset');
    Route::post('nova-senha', [PasswordController::class, 'store'])->middleware(ProtectAgainstSpam::class)->name('admin.password.store');
    //--FIMrotas para redefinição de senha

    Route::prefix('login')->controller(LoginController::class)->group(function(){
        Route::get('', 'index')->name('admin.login.index');
        Route::post('/logar','logar')->middleware(ProtectAgainstSpam::class)->name('admin.login.logar');
        Route::get('/logout', 'destroy')->name('admin.login.logout');
    });

    Route::prefix('dashboard')->middleware(['auth', 'auth.session'])->controller(DashboardController::class)->group(function(){
        Route::get('/', 'index')->name('dashboard.index');
    });

});
