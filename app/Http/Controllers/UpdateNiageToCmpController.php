<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTphDownload;
use App\Models\KoordinatatTph;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use ZipArchive;
use Illuminate\Support\Facades\Storage;


class UpdateNiagaToCmpController extends Controller
{

    // untuk di niaga 

    public function downloadData()
    {
        try {
            // Generate unique request ID
            $requestId = uniqid();

            // Dispatch job
            ProcessTphDownload::dispatch($requestId);

            return response()->json([
                'status' => 200,
                'message' => 'Download process started',
                'request_id' => $requestId
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting download: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to start download process',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkDownloadStatus($requestId)
    {
        $status = Cache::get("tph_download_{$requestId}_status");
        if (!$status) {
            return response()->json([
                'status' => 404,
                'message' => 'Download request not found'
            ], 404);
        }

        $response = [
            'status' => 200,
            'download_status' => $status
        ];

        if ($status === 'processing') {
            $response['total'] = Cache::get("tph_download_{$requestId}_total", 0);
            $response['processed'] = Cache::get("tph_download_{$requestId}_processed", 0);
        } elseif ($status === 'completed') {
            $response['download_url'] = Cache::get("tph_download_{$requestId}_url");
        } elseif ($status === 'failed') {
            $response['error'] = Cache::get("tph_download_{$requestId}_error");
        }

        return response()->json($response);
    }
    public function cleanupOldDownloads()
    {
        try {
            $files = Storage::files('downloads');
            foreach ($files as $file) {
                if (Storage::lastModified($file) < now()->subHour()->timestamp) {
                    Storage::delete($file);
                }
            }
            return response()->json(['status' => 200, 'message' => 'Cleanup successful']);
        } catch (\Exception $e) {
            Log::error('Cleanup error: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Cleanup failed']);
        }
    }

    public function getLatestZip()
    {
        try {
            // Cek file di private storage
            $files = Storage::disk('private')->files('downloads');

            // Filter hanya file ZIP
            $zipFiles = array_filter($files, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
            });

            if (empty($zipFiles)) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No ZIP files found'
                ], 404);
            }

            // Sort berdasarkan last modified time
            usort($zipFiles, function ($a, $b) {
                return Storage::disk('private')->lastModified($b) - Storage::disk('private')->lastModified($a);
            });

            // Ambil file terbaru
            $latestFile = $zipFiles[0];
            $fileName = basename($latestFile);

            // Return file untuk didownload
            return Storage::disk('private')->download($latestFile, $fileName, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting latest ZIP: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to get latest ZIP file',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function checkLastUpdate()
    {
        try {
            $lastUpdate = KoordinatatTph::orderBy('update_date', 'desc')
                ->value('update_date');

            return response()->json([
                'status' => $lastUpdate ? 200 : 404,
                'message' => $lastUpdate ? 'Success' : 'No data found',
                'last_update' => $lastUpdate
            ], $lastUpdate ? 200 : 404);
        } catch (\Exception $e) {
            Log::error('Error checking last update: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'Failed to check last update',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
