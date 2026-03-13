<?php

namespace App\Models;

use App\Observers\ServicosObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

#[ObservedBy([ServicosObserver::class])]
class Servicos extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_servicos';
    protected $primaryKey = 'se_id';
    protected $fillable = [
        'se_id',
        'se_contas_id',
        'se_nome',
        'se_cod',
        'se_email_vinculado',
        'se_username',
        'se_senha_atual',
        'se_senha_anterior',
        'se_data_renovacao',
        'se_data_update',
        'se_status',
        'se_tipo',
        'se_qtd_assinantes',
        'se_qtd_upate',
        'se_limite',
        'created_at',
        'updated_at'
    ];

    /*
    protected static function booted() : void
    {

         //Usamos 'updating' (ANTES de ir para o banco)
        static::updating(function ($model) {
            // 1. Verifica se a senha foi alterada na requisição atual
            // (Ajuste 'se_senha_atual' para o nome exato da sua coluna de senha)
            if ($model->isDirty('se_senha_atual')) {
                // 2. Injeta a data atual na coluna de data de troca
                // (Ajuste 'se_senha_atual' para o nome da sua coluna)
                $senhaAntiga = $model->getOriginal('se_senha_atual');

                $model->se_data_update = date('Y-m-d');
                $model->se_senha_anterior = $senhaAntiga;
                $model->se_qtd_assinantes = 0;

            }

            if($model->se_status == 'ativa'){

            }

        });

        static::updated(function($servico) {
            // 1. Verifica se o campo de senha sofreu alteração nesta requisição
            // (Ajuste 'password' para o nome real da sua coluna, ex: 'senha' ou 'cl_senha')
            if ($servico->wasChanged('se_status')) {
                // 2. Pega a senha NOVA (A que acabou de ser salva)
                if($servico->se_status == 'fechada'){

                }
            }
        });
    }
    */

    protected function casts(): array
    {
        return [
            'se_data_renovacao' => 'date:d/m/Y',
            'se_data_update' => 'date:d/m/Y',
            'created_at' => 'datetime:d/m/Y',
            'updated_at' => 'datetime:d/m/Y',
        ];
    }

    public function conta(): BelongsTo
    {
        return $this->belongsTo(Contas::class, 'se_contas_id', 'co_id');
    }

    public function clientes()
    {
        return $this->belongsToMany(
            Clientes::class,
            'cv_clientes_servicos',
            'cs_servico_id',
            'cs_cliente_id'
        )->withPivot(['cs_status', 'cs_data_termino', 'cs_aviso_vencimento']);
    }

}
