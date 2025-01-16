<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql3';
    protected $table = 'pengguna';
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'user_id'; // sesuaikan dengan kolom primary key Anda
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->user_id; // sesuaikan dengan kolom primary key Anda
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function jabatanpengguna()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id');
    }
}
