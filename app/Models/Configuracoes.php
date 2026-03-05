<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracoes extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_configuracoes';
    protected $primaryKey = 'co_id';
    public $timestamps = false;
    protected $fillable = [
        'co_id',
        'co_key',
        'co_value',
        'co_group',
    ];

    protected static function booted()
    {
        // Sempre que salvar ou deletar, limpa o cache
        static::saved(fn () => Cache::forget('app_settings'));
        static::deleted(fn () => Cache::forget('app_settings'));
    }

    public static function get($key, $default = null)
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return self::all()->pluck('co_value', 'co_key');
        });

        return $settings[$key] ?? $default;
    }

}
