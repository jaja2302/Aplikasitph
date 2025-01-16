<?php

use App\Models\Departement;
use App\Models\Jabatan;
use App\Models\Pengguna;



if (!function_exists('check_previlege')) {
    function check_previlege($user_id)
    {
        $pengguna = Pengguna::with('jabatanpengguna')
            ->where('user_id', $user_id)
            ->first();

        if (!$pengguna || !$pengguna->jabatanpengguna) {
            return false;
        }

        return $pengguna->jabatanpengguna->id_level > 4;
    }
}
