<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $authPasswordName = 'us_password';
    protected $rememberTokenName = 'us_remember_token';
    protected $guard = 'web';

    protected $table = 'cv_users';
    protected $primaryKey = 'us_id';
    protected $fillable = [
        'us_nome',
        'us_email',
        'email_verified_at',
        'us_remember_token',
        'us_password',
        'us_status',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'us_password',
        'us_remember_token',
    ];

    public function routeNotificationForMail(Notification $notification): array|string
    {
        // Return email address only...
        return $this->us_email;
        // Return email address and name...
        //return [$this->us_email => $this->us_nome];
    }

    public function getEmailForVerification()
    {
        return $this->us_email;
    }

    public function getEmailForPasswordReset()
    {
        return $this->us_email;
    }

    public function dataBr()
    {
        $data = \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
        return $data;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'us_password' => 'hashed',
            'created_at' => 'datetime:d/m/Y H:i:s',
            'updated_at' => 'datetime:d/m/Y H:i:s',
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['us_password'] = Hash::make($value);
    }

    public function situacao()
    {
        $status = ($this->us_status == 1) ? 'Ativo' : 'Inativo';
        return $status;
    }

}
