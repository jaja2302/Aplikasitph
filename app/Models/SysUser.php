<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SysUser extends Authenticatable
{
    //

    // id
    // username
    // password
    // nik
    // nama
    // jabatan
    // token
    // ip
    // cp
    // email
    // wa
    // gperm
    // create_by
    // create_date
    // update_by
    // update_date
    // history
    // status

    use HasFactory, Notifiable;
    protected $table = 'sys_user';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public $timestamps = false;

    // public function BbcData()
    // {
    //     return $this->hasMany(BbcData::class, 'estate', 'id')->on('mysql2');
    // }
}
