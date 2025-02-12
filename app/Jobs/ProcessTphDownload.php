<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\KoordinatatTph;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessTphDownload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout
    private $requestId;

    /**
     * Create a new job instance.
     */

    public function __construct($requestId)
    {
        $this->requestId = $requestId;
        $this->queue = 'tph_downloads'; // Set specific queue name
    }

    public function handle()
    {
        try {
            // Update status to processing
            Cache::put("tph_download_{$this->requestId}_status", 'processing', 3600);

            // Generate filenames
            $filename = "tph_data_{$this->requestId}.json";
            $zipname = "tph_data_{$this->requestId}.zip";

            // Ensure directory exists
            if (!Storage::exists('downloads')) {
                Storage::makeDirectory('downloads');
            }

            // Get total records and update cache
            $total = KoordinatatTph::count();
            Cache::put("tph_download_{$this->requestId}_total", $total, 3600);

            // Open file for streaming write
            $handle = fopen(Storage::path('downloads/' . $filename), 'w');
            fwrite($handle, '{"data":[');

            $first = true;
            $processedCount = 0;

            // Process in chunks
            KoordinatatTph::orderBy('update_date', 'desc')
                ->chunk(100, function ($records) use ($handle, &$first, &$processedCount) {
                    foreach ($records as $record) {
                        if (!$first) {
                            fwrite($handle, ',');
                        }
                        fwrite($handle, json_encode($record, JSON_UNESCAPED_UNICODE));
                        $first = false;
                        $processedCount++;

                        // Update progress in cache
                        Cache::put("tph_download_{$this->requestId}_processed", $processedCount, 3600);
                    }
                });

            fwrite($handle, ']}');
            fclose($handle);

            // Create ZIP
            $zip = new ZipArchive();
            $zipPath = Storage::path('downloads/' . $zipname);

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $zip->addFile(Storage::path('downloads/' . $filename), $filename);
                $zip->close();

                // Delete JSON file
                Storage::delete('downloads/' . $filename);

                // Save success status and download URL
                Cache::put("tph_download_{$this->requestId}_status", 'completed', 3600);
                Cache::put(
                    "tph_download_{$this->requestId}_url",
                    Storage::url('downloads/' . $zipname),
                    3600
                );
            } else {
                throw new \Exception("Failed to create ZIP file");
            }
        } catch (\Exception $e) {
            Log::error('TPH Download Job Error: ' . $e->getMessage());

            // Cleanup
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            if (Storage::exists('downloads/' . $filename)) {
                Storage::delete('downloads/' . $filename);
            }
            if (isset($zipPath) && file_exists($zipPath)) {
                unlink($zipPath);
            }

            // Save error status
            Cache::put("tph_download_{$this->requestId}_status", 'failed', 3600);
            Cache::put("tph_download_{$this->requestId}_error", $e->getMessage(), 3600);
        }
    }
}
