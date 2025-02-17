<?php

namespace App\Models\CMP;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mysql2';
    protected $table = 'sys_user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'update_date',
        'api_token',
        // add other fields that you want to be mass assignable
    ];

    public $timestamps = false;
}
