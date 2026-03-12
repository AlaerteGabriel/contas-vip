<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Configurar o cron a cada minuto para que isso funcione.

//* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1

Schedule::command('app:limpar-servicos')->dailyAt('21:00');
Schedule::command('app:verifica-assinatura-cliente')->dailyAt('14:00');

//Schedule::command('app:verifica-assinatura')->dailyAt('12:00');
//Schedule::command('app:limpar-servicos')->everyMinute();
