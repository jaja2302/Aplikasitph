<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class FetchController extends Controller
{
    //
    public function fetch()
    {
        return response()->json(['status' => 200, 'message' => 'Cleanup successful']);
    }

    // untuk cmp  

    protected $batchSize = 1000;
    protected $niagaUrl = "https://tph.srs-ssms.com/api/sync/tph/latest-zip";

    public function fetchNiagaData()
    {
        // dd('test');
        try {
            DB::beginTransaction();

            // Create temp directory if not exists
            $tempPath = storage_path('app/temp');
            if (!is_dir($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // Download ZIP file langsung dari latest-zip endpoint
            $response = Http::timeout(30)->get($this->niagaUrl);
            if (!$response->successful()) {
                throw new \Exception('Failed to download ZIP file: ' . $response->status());
            }

            // Save ZIP file
            $zipPath = $tempPath . '/temp.zip';
            file_put_contents($zipPath, $response->body());

            // Extract ZIP
            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== TRUE) {
                throw new \Exception('Failed to open ZIP file');
            }

            // Extract to temp directory
            $zip->extractTo($tempPath);
            $zip->close();

            // Find JSON file
            $jsonFiles = glob($tempPath . '/*.json');
            if (empty($jsonFiles)) {
                throw new \Exception('No JSON file found in ZIP');
            }

            // Read and parse JSON
            $jsonContent = json_decode(file_get_contents($jsonFiles[0]), true);
            if (!isset($jsonContent['data'])) {
                throw new \Exception('Invalid JSON structure');
            }

            $data = $jsonContent['data'];
            $totalRows = count($data);

            if (empty($data)) {
                return response()->json([
                    'status' => 200,
                    'message' => 'No data to sync',
                    'total_rows' => 0,
                    'processed_rows' => 0
                ]);
            }

            $processedCount = $this->processBatchUpdate($data);

            DB::commit();

            // Get ZIP file info for logging
            $zipInfo = [
                'file_name' => basename($jsonFiles[0], '.json') . '.zip',
                'last_modified' => date('Y-m-d H:i:s', filemtime($zipPath))
            ];

            Log::info("Successfully synced {$processedCount} records from ZIP: {$zipInfo['file_name']}");

            // Cleanup temp files
            unlink($zipPath);
            array_map('unlink', glob($tempPath . '/*.json'));

            return response()->json([
                'status' => 200,
                'message' => 'Data successfully synchronized from ZIP',
                'total_rows' => $totalRows,
                'processed_rows' => $processedCount,
                'zip_info' => $zipInfo
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync error: ' . $e->getMessage());

            // Cleanup temp files if they exist
            if (isset($zipPath) && file_exists($zipPath)) {
                unlink($zipPath);
            }
            if (isset($tempPath)) {
                array_map('unlink', glob($tempPath . '/*.json'));
            }

            return response()->json([
                'status' => 500,
                'message' => 'Sync failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    protected function processBatchUpdate(array $data)
    {
        $processedCount = 0;

        DB::connection('mysql2')->beginTransaction(); // Start transaction

        try {
            foreach (array_chunk($data, $this->batchSize) as $batch) {
                $batchData = [];

                foreach ($batch as $item) {
                    $id = $item['id'];
                    unset($item['id']);
                    $batchData[] = array_merge(['id' => $id], $item);
                }

                DB::connection('mysql2')->table('tph')->upsert($batchData, ['id']);
                $processedCount += count($batchData);
            }

            DB::connection('mysql2')->commit(); // Commit kalau berhasil
        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack(); // Rollback kalau error
            throw $e;
        }

        return $processedCount;
    }
}
