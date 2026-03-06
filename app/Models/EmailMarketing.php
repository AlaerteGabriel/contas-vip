<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailMarketing extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_email_marketing';
    protected $primaryKey = 'em_id';
    protected $fillable = [
        'em_titulo',
        'em_template_id',
        'em_limite_envios',
        'em_filtros_aplicados',
        'em_status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'em_filtros_aplicados' => 'array',
    ];

    // Se você tiver uma tabela de templates de e-mail
    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplatesEmail::class, 'em_template_id', 'te_id');
    }

    // Relação com os logs de envio (quem recebeu o quê)
    public function logs(): HasMany
    {
        return $this->hasMany(EmailMarketingLog::class, 'eml_email_marketing_id', 'em_id');
    }

}
