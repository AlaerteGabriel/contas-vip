<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'se_nome',
        'se_cod',
        'se_email_vinculado',
        'se_username',
        'se_senha_atual',
        'se_senha_anterior',
        'se_data_renovacao',
        'se_status',
        'se_data_ult_assinatura',
        'se_qtd_assinantes',
        'se_ass_hoje',
        'created_at',
        'updated_at'
    ];

}
