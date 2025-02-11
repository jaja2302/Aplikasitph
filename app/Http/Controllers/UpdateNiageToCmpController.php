<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateNiageToCmpController extends Controller
{
    //
    public function updateNiageToCmp()
    {
        try {
            // Menggunakan chunk untuk mengambil data secara bertahap
            $chunkSize = 1000;
            $totalUpdated = 0;

            DB::connection('mysql3')->table('tph')
                ->orderBy('id')
                ->chunk($chunkSize, function ($records) use (&$totalUpdated) {
                    $updates = [];

                    foreach ($records as $record) {
                        // Convert record object to array
                        $updates[] = (array) $record;
                    }

                    // Batch update untuk setiap chunk
                    if (!empty($updates)) {
                        DB::connection('mysql2')->table('tph')
                            ->upsert(
                                $updates,
                                ['id'], // Primary key untuk identifikasi record
                                [ // Kolom yang akan diupdate
                                    'regional',
                                    'company',
                                    'company_ppro',
                                    'company_abbr',
                                    'company_nama',
                                    'dept',
                                    'dept_ppro',
                                    'dept_abbr',
                                    'dept_nama',
                                    'divisi',
                                    'divisi_ppro',
                                    'divisi_abbr',
                                    'divisi_nama',
                                    'blok',
                                    'blok_kode',
                                    'blok_nama',
                                    'ancak',
                                    'kode_tph',
                                    'nomor',
                                    'tahun',
                                    'lat',
                                    'lon',
                                    'user_input',
                                    'app_version',
                                    'create_by',
                                    'create_nama',
                                    'create_date',
                                    'update_by',
                                    'update_nama',
                                    'update_date',
                                    'history',
                                    'status'
                                ]
                            );

                        $totalUpdated += count($updates);
                    }
                });

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengupdate $totalUpdated records"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ], 500);
        }
    }
}
