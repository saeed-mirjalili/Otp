<?php

namespace Saeed\Otp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OtpUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'otp_users';

    protected $guarded;
}
