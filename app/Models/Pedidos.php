<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\UtilHelper AS HP;

class Pedidos extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_pedidos';
    protected $primaryKey = 'pe_id';
    protected $fillable = [
        'pe_id',
        'pe_cliente_id',
        'pe_sku',
        'pe_detalhes',
        'pe_servico',
        'pe_tipo_venda',
        'pe_data_inicio',
        'pe_data_termino',
        'pe_periodo',
        'pe_data_pedido',
        'pe_obs',
        'created_at',
        'updated_at'
    ];

    //Um pedido pertence a um cliente (logo o pedido é filho do cliente, utiliza-se belongsTo
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'pe_cliente_id', 'cl_id');
    }


}
