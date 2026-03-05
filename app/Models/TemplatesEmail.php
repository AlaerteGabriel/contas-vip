<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatesEmail extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'cv_templates_email';
    protected $primaryKey = 'te_id';
    protected $fillable = [
        'te_id',
        'te_codigo',
        'te_assunto',
        'te_modelo',
        'created_at',
        'updated_at'
    ];

}
