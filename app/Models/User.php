<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\OtpAktivasiMail;
use App\Mail\OtpMail;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'users';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function audit_answers()
    {
        return $this->hasMany(AuditAnswer::class, 'auditor_id', 'id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'nik',
        'device_id',
        'otp',
        'otp_expires_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateOtpAktivasi()
    {
        $this->otp = random_int(100000, 999999); // 6 digit otp numbers
        $this->otp_expires_at = Carbon::now()->addMinutes(10);
        $this->save();

        Mail::to($this->email)->send(new OtpAktivasiMail($this->otp));
    }

    public function generateOtpReset()
    {
        $this->otp = random_int(100000, 999999); // 6 digit otp numbers
        $this->otp_expires_at = Carbon::now()->addMinutes(10);
        $this->save();

        Mail::to($this->email)->send(new ResetPasswordMail($this->otp));
    }
}
