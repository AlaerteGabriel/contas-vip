<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailMarketingLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_email_marketing_logs';
    protected $primaryKey = 'eml_id';
    protected $fillable = [
        'eml_titulo',
        'eml_email_marketing_id',
        'eml_user_id',
        'eml_email_destino',
        'eml_status',
        'eml_msg',
        'eml_enviado_em',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'eml_enviado_em' => 'datetime',
    ];

    /**
     * Retorna a campanha a qual este log pertence.
     */
    public function campanha(): BelongsTo
    {
        return $this->belongsTo(EmailMarketing::class, 'eml_email_marketing_id', 'em_id');
    }

    /**
     * Retorna o usuário que recebeu o e-mail.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'eml_user_id', 'cl_id');
    }
}
