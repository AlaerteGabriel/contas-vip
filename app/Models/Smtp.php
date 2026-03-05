<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_smtp';
    protected $primaryKey = 'sm_id';
    protected $fillable = [
        'sm_nome',
        'sm_email_remetente',
        'sm_host',
        'sm_login',
        'sm_senha',
        'sm_protocolo',
        'sm_padrao',
        'sm_porta',
        'created_at',
        'updated_at'
    ];

}
