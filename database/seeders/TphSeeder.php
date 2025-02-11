<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;

class TphSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $filePath = storage_path('app/tph.csv');
        if (!file_exists($filePath)) {
            echo "File CSV tidak ditemukan: $filePath\n";
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setDelimiter(';'); // Sesuaikan dengan format CSV
        $csv->setHeaderOffset(0); // Baris pertama dianggap header

        $batchSize = 1000; // Insert per 1000 baris untuk efisiensi
        $dataBatch = [];

        foreach ($csv as $record) {
            $dataBatch[] = $record;

            if (count($dataBatch) >= $batchSize) {
                DB::connection('mysql5')->table('tph')->insert($dataBatch);
                $dataBatch = [];
            }
        }

        if (count($dataBatch) > 0) {
            DB::connection('mysql5')->table('tph')->insert($dataBatch);
        }

        echo "Data berhasil di-insert ke tabel tph.\n";
    }
}
