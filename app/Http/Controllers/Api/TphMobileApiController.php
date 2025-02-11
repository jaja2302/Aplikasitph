<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\KoordinatatTph;

class TphMobileApiController extends Controller
{
    //

    public function storeDataTPHKoordinat(Request $request)
    {
        try {
            // Decode the Base64 data
            $decodedData = base64_decode($request->input('data'));
            if ($decodedData === false) {

                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid Base64 data',
                    'data' => null
                ], 400);
            }

            // Decompress the GZIP data
            $unzippedData = gzdecode($decodedData);
            if ($unzippedData === false) {

                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid GZIP data',
                    'data' => null
                ], 400);
            }

            // Convert JSON string to an array
            $dataList = json_decode($unzippedData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {

                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid JSON data',
                    'data' => null
                ], 400);
            }



            // Initialize arrays for tracking
            $successfullyProcessed = [];
            $failedToProcess = [];

            // Begin transaction
            DB::beginTransaction();

            foreach ($dataList as $data) {
                // Validate data
                $validator = Validator::make($data, [
                    'dept' => 'required|max:255',
                    'divisi' => 'required|max:255',
                    'blok' => 'required|max:255',
                    'tahun' => 'required|max:255',
                    'nomor' => 'required|max:255',
                    'lat' => 'required|numeric',
                    'lon' => 'required|numeric',
                    'user_input' => 'required|max:255',
                    'app_version' => 'nullable|max:255',
                ]);

                if ($validator->fails()) {
                    $error = $validator->errors()->first();
                    $failedToProcess[] = [
                        'data' => $data,
                        'error' => $error
                    ];

                    continue;
                }

                try {
                    // Search for an existing record
                    $existingRecord = KoordinatatTph::where([
                        'id' => (int) $data['id_tph'],
                        'dept' => $data['dept'],
                        'divisi' => $data['divisi'],
                        'blok' => $data['blok'],
                        'tahun' => $data['tahun'],
                        'nomor' => $data['nomor'],
                    ])->first();

                    if ($existingRecord) {

                        // Update the existing record
                        $existingRecord->update([
                            'lat' => $data['lat'],
                            'lon' => $data['lon'],
                            'user_input' => $data['user_input'],
                            'app_version' => $data['app_version'],
                            'update_date' => Carbon::now()->format('Y-m-d H:i:s')
                        ]);
                        $successfullyProcessed[] = $existingRecord->toArray();
                    } else {

                        // Log missing record and continue
                        $failedToProcess[] = [
                            'data' => $data,
                            'error' => 'Data not found'
                        ];
                    }
                } catch (\Exception $e) {
                    // Log update errors
                    $failedToProcess[] = [
                        'data' => $data,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Commit or rollback transaction based on success
            if (!empty($successfullyProcessed)) {
                DB::commit();
            } else {
                DB::rollBack();
            }

            // Final response
            return response()->json([
                'statusCode' => 1,
                'message' => sprintf(
                    'Processed %d records: %d successful, %d failed',
                    count($dataList),
                    count($successfullyProcessed),
                    count($failedToProcess)
                ),
                'data' => [
                    'stored' => $successfullyProcessed,
                    'failed' => $failedToProcess
                ]
            ]);
        } catch (\Exception $e) {
            // Handle unexpected errors
            DB::rollBack();

            return response()->json([
                'statusCode' => 0,
                'message' => 'Error processing request: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function testApi(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'API is working!',
            'data' => [
                'timestamp' => now(),
                'environment' => app()->environment(),
                'test_data' => [
                    'name' => 'TPH API',
                    'version' => '1.0',
                    'features' => ['koordinat', 'mobile', 'tracking']
                ]
            ]
        ]);
    }
}
