<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCMPserver extends Seeder
{
    public function run()
    {
        // Path ke file JSON
        $jsonFile = storage_path('app/export_tph_2025-02-11.json');

        // Membaca file JSON
        $json = file_get_contents($jsonFile);
        $data = json_decode($json, true);

        // Menggunakan raw query untuk memperbarui data
        foreach ($data as $estateData) {
            DB::connection('mysql2')->table('tph')
                ->where('id', $estateData['id'])
                ->update([
                    'regional' => $estateData['regional'],
                    'company' => $estateData['company'],
                    'company_ppro' => $estateData['company_ppro'],
                    'company_abbr' => $estateData['company_abbr'],
                    'company_nama' => $estateData['company_nama'],
                    'dept' => $estateData['dept'],
                    'dept_ppro' => $estateData['dept_ppro'],
                    'dept_abbr' => $estateData['dept_abbr'],
                    'dept_nama' => $estateData['dept_nama'],
                    'divisi' => $estateData['divisi'],
                    'divisi_abbr' => $estateData['divisi_abbr'],
                    'divisi_ppro' => $estateData['divisi_ppro'],
                    'divisi_nama' => $estateData['divisi_nama'],
                    'blok' => $estateData['blok'],
                    'blok_kode' => $estateData['blok_kode'],
                    'blok_nama' => $estateData['blok_nama'],
                    'ancak' => $estateData['ancak'],
                    'kode_tph' => $estateData['kode_tph'],
                    'nomor' => $estateData['nomor'],
                    'tahun' => $estateData['tahun'],
                    'lat' => $estateData['lat'],
                    'lon' => $estateData['lon'],
                    'user_input' => $estateData['user_input'],
                    'app_version' => $estateData['app_version'],
                    'create_by' => $estateData['create_by'],
                    'create_date' => $estateData['create_date'],
                    'create_nama' => $estateData['create_nama'],
                    'update_by' => $estateData['update_by'],
                    'update_nama' => $estateData['update_nama'],
                    'update_date' => $estateData['update_date'],
                    'history' => $estateData['history'],
                    'status' => $estateData['status'],
                ]);
        }

        $this->command->info('Estate data updated successfully!');
    }
}
