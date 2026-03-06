<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\EmailMarketingController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\TemplatesEmailController;
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

    Route::prefix('dashboard')->middleware(['auth', 'auth.session'])->group(function(){

        Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::prefix('/perfil')->controller(PerfilController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.perfil.index');
            Route::post('/update', 'update')->name('dashboard.perfil.update');
            Route::post('/pass-update', 'passwordUpdate')->name('dashboard.password.update');
        });

        Route::prefix('/clientes')->controller(ClientesController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.clientes.index');
            Route::get('/add', 'create')->name('dashboard.clientes.create');
            Route::post('/add', 'store')->name('dashboard.clientes.store');
        });

        Route::prefix('/servicos')->controller(ServicosController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.servicos.index');
            Route::get('/add', 'create')->name('dashboard.servicos.create');
            Route::post('/add', 'store')->name('dashboard.servicos.store');
        });

        Route::prefix('/pedidos')->controller(PedidosController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.pedidos.index');
            Route::get('/add', 'create')->name('dashboard.pedidos.create');
            Route::post('/add', 'store')->name('dashboard.pedidos.store');
        });

        Route::prefix('/config')->controller(ConfiguracoesController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.config.index');
            Route::get('/importar-excel', 'importar')->name('dashboard.config.importarExcel');
            Route::post('/produtosstorecsv', 'produtosStoreCsv')->name('dashboard.config.produtosStoreCsv');
        });

        Route::prefix('/smtp')->controller(SmtpController::class)->group(function(){
            Route::get('/mail-server', 'smtp')->name('dashboard.config.mail-server');
            Route::post('/mail-server', 'smtpStore')->name('dashboard.config.mail-server.store');
            Route::post('/delete', 'ajaxDestroy')->name('dashboard.smtp.ajaxDestroy');
            Route::get('/get-smtp', 'getDatatablesSmtp')->name('dashboard.smtp.getDatatablesSmtp');
            Route::get('/smtp-edit', 'ajaxSmtpEdit')->name('dashboard.smtp.ajaxSmtpEdit');
        });

        Route::prefix('/templates-email')->controller(TemplatesEmailController::class)->group(function(){
            Route::get('/', 'index')->name('dashboard.config.email-templates');
            Route::post('/store', 'store')->name('dashboard.config.email-templates.store');
            Route::post('/delete', 'ajaxDestroy')->name('dashboard.config.email-templates.ajaxDestroy');
            Route::get('/get-templates', 'getDatatablesEmailTemplates')->name('dashboard.config.email-templates.getDatatablesEmailTemplates');
            Route::get('/edit', 'ajaxEdit')->name('dashboard.config.email-templates.ajaxEdit');
        });

        Route::prefix('/email-marketing')->controller(EmailMarketingController::class)->group(function(){

            Route::get('/get-contatos', 'criar')->name('dashboard.config.email-marketing.getContatos');
            Route::get('/', 'index')->name('dashboard.config.email-marketing');
            Route::post('/store', 'store')->name('dashboard.config.email-marketing.store');
        });

    });

});
