<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contas extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_contas';
    protected $primaryKey = 'co_id';
    protected $fillable = [
        'co_id',
        'co_nome',
        'co_codigo',
        'co_url',
        'co_url_anuncio',
        'co_limite',
        'co_envio_manual',
        'co_template_email_id',
        'created_at',
        'updated_at'
    ];

    public function servicos(): HasMany
    {
        return $this->hasMany(Servicos::class, 'se_contas_id', 'co_id');
    }

}
