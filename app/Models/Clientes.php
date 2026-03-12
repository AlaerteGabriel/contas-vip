<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\UtilHelper AS HP;

class Clientes extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_clientes';
    protected $primaryKey = 'cl_id';
    protected $fillable = [
        'cl_id',
        'cl_nome',
        'cl_usuario',
        'cl_email',
        'cl_email_envio',
        'cl_cel',
        'cl_banido',
        'cl_observacao',
        'created_at',
        'updated_at'
    ];

    //Cliente tem muitos pedido então utilizamos hasMany
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedidos::class, 'pe_cliente_id', 'cl_id');
    }

    public function servicos()
    {
        return $this->belongsToMany(
            Servicos::class,
            'cv_clientes_servicos', // Nome da tabela pivot
            'cs_cliente_id',         // FK do cliente na pivot
            'cs_servico_id'          // FK do serviço na pivot
        )
            ->withPivot(['cs_status', 'cs_data_termino', 'cs_aviso_vencimento']);
    }

    public function assinaturas()
    {
        // Um cliente tem vários registros de controle (assinaturas)
        return $this->hasMany(Controle::class, 'cs_cliente_id', 'cl_id');
    }

}
