<?php

use App\Models\Departement;
use App\Models\Jabatan;
use App\Models\Pengguna;
use App\Models\SysUser;
use Illuminate\Support\Facades\Auth;

// if (!function_exists('check_previlege')) {
//     function check_previlege($user_id)
//     {
//         $pengguna = Pengguna::with('Jabatan', 'Departement')
//             ->where('user_id', $user_id)
//             ->first();

//         if (!$pengguna || !$pengguna->Jabatan) {
//             return false;
//         }

//         // Debugging output
//         // dd($pengguna->Departement);

//         if ($pengguna->Departement->isEmpty()) {
//             return false;
//         }


//         // 1 = srs 
//         $departementFamily = array_unique(GetDepartementFamily(1));

//         // dd($departementFamily);
//         if (in_array($pengguna->Departement[0]->id, $departementFamily)) {
//             if ($pengguna->Jabatan->id_level >= 4) {
//                 return true;
//             }
//         }
//         return false;
//     }
// }

if (!function_exists('GetDepartementFamily')) {
    function GetDepartementFamily($parentId)
    {
        $allDepartments = Departement::all();

        // Function to get all child departments of a given parent department
        $getChildDepartments = function ($parentId, $departments) use (&$getChildDepartments) {
            $childDepartments = [];
            foreach ($departments as $department) {
                if ($department->id_parent == $parentId) {
                    $childDepartments[] = $department->id;
                    // Recursively get child departments
                    $childDepartments = array_merge($childDepartments, $getChildDepartments($department->id, $departments));
                }
            }
            $childDepartments[] = $parentId;
            return $childDepartments;
        };
        return $getChildDepartments($parentId, $allDepartments);
    }
}
if (!function_exists('check_previlege_cmp')) {
    function check_previlege_cmp()
    {
        $user_jabatan = Auth::user()->jabatan;

        // dd($user_jabatan, Auth::user());
        if ($user_jabatan === 'IT') {
            return true;
        }
        return false;
    }
}
// if (!function_exists('is_dep_da')) {
//     function is_dep_da()
//     {
//         $pengguna = Pengguna::with(['Jabatan', 'Departement'])
//             ->where('user_id', auth()->id())
//             ->first();

//         if (!$pengguna || !$pengguna->Departement) {
//             return false;
//         }

//         // Ensure Departement is a collection or object
//         if (is_a($pengguna->Departement, 'Illuminate\Database\Eloquent\Collection')) {
//             return $pengguna->Departement->contains('nama', 'Digital Architect');
//         }

//         // If Departement is a single object
//         return $pengguna->Departement->nama === 'Digital Architect';
//     }
// }
