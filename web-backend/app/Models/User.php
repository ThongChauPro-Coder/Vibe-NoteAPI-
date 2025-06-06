<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'userId';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'userName',
        'userEmail',
        'userPassword',
    ];

    protected $hidden = [
        'userPassword',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->userPassword;
    }
    
    protected $guarded = [];
}

