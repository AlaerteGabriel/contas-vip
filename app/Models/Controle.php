<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Controle extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_clientes_servicos';
    protected $primaryKey = 'cs_id';
    public $timestamps = false;
    protected $fillable = [
        'cs_cliente_id',
        'cs_servico_id',
        'cs_pedido_id',
        'cs_template_email_id',
        'cs_data_inicio',
        'cs_data_termino',
        'cs_cod_email',
        'cs_aviso_vencimento',
        'cs_status'
    ];

    protected static function booted(): void
    {
        static::deleted(function ($controle) {
            if($controle->cs_servico_id){
                Servicos::where('se_id', $controle->cs_servico_id)->decrement('se_qtd_assinantes');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'cs_data_inicio' => 'date:d/m/Y',
            'cs_data_termino' => 'date:d/m/Y',
        ];
    }

    public function cliente() : BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'cs_cliente_id', 'cl_id');
    }

    public function servico() : BelongsTo
    {
        return $this->belongsTo(Servicos::class, 'cs_servico_id', 'se_id');
    }

    public function pedido() : BelongsTo
    {
        return $this->belongsTo(Pedidos::class, 'cs_pedido_id', 'pe_id');
    }

    public function templateEmail() : BelongsTo
    {
        return $this->belongsTo(TemplatesEmail::class, 'cs_template_email_id', 'te_id');
    }

}
