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
            $decodedData = base64_decode($request->input('data'));
            if ($decodedData === false) {
                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid Base64 data',
                    'data' => null
                ], 400);
            }

            $unzippedData = gzdecode($decodedData);
            if ($unzippedData === false) {
                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid GZIP data',
                    'data' => null
                ], 400);
            }

            $dataList = json_decode($unzippedData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'statusCode' => false,
                    'message' => 'Invalid JSON data',
                    'data' => null
                ], 400);
            }

            $successfullyProcessed = [];
            $failedToProcess = [];

            DB::beginTransaction();

            foreach ($dataList as $data) {
                try {
                    if (is_string($data['app_version'])) {
                        $data['app_version'] = json_decode($data['app_version'], true);
                    }

                    $validator = Validator::make($data, [
                        'dept' => 'required',
                        'divisi' => 'required',
                        'blok' => 'required',
                        'tahun' => 'required',
                        'nomor' => 'required',
                        'lat' => 'required|numeric',
                        'lon' => 'required|numeric',
                        'user_input' => 'required',
                        'app_version' => 'required|array',
                        'app_version.app_version' => 'required|string',
                        'app_version.os_version' => 'required|string',
                        'app_version.device_model' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        \Log::error('Validation failed for TPH data', [
                            'data' => $data,
                            'errors' => $validator->errors()->all()
                        ]);
                        $failedToProcess[] = [
                            'data' => $data,
                            'error' => $validator->errors()->first()
                        ];
                        continue;
                    }

                    $exists = DB::connection('mysql3')
                        ->select(
                            "SELECT id FROM tph WHERE 
                            id = ? AND 
                            dept = ? AND 
                            divisi = ? AND 
                            blok = ? AND 
                            tahun = ? AND 
                            nomor = ?",
                            [
                                (int) $data['id_tph'],
                                $data['dept'],
                                $data['divisi'],
                                $data['blok'],
                                $data['tahun'],
                                $data['nomor']
                            ]
                        );

                    if (!empty($exists)) {
                        $updated = DB::connection('mysql3')
                            ->update(
                                "UPDATE tph SET 
                                lat = ?,
                                lon = ?,
                                user_input = ?,
                                app_version = ?,
                                update_date = ?,
                                status = 1
                                WHERE id = ?",
                                [
                                    $data['lat'],
                                    $data['lon'],
                                    $data['user_input'],
                                    json_encode($data['app_version']),
                                    Carbon::now()->format('Y-m-d H:i:s'),
                                    (int) $data['id_tph']
                                ]
                            );

                        if ($updated) {
                            $successfullyProcessed[] = $data;
                        } else {
                            \Log::error('Failed to update TPH record', [
                                'id' => $data['id_tph']
                            ]);
                            $failedToProcess[] = [
                                'data' => $data,
                                'error' => 'Failed to update record'
                            ];
                        }
                    } else {
                        \Log::warning('TPH record not found', [
                            'search_criteria' => $data
                        ]);
                        $failedToProcess[] = [
                            'data' => $data,
                            'error' => 'Data not found'
                        ];
                    }
                } catch (\Exception $e) {
                    \Log::error('Error processing TPH record', [
                        'data' => $data,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $failedToProcess[] = [
                        'data' => $data,
                        'error' => $e->getMessage()
                    ];
                }
            }

            if (!empty($successfullyProcessed)) {
                DB::commit();
            } else {
                DB::rollBack();
            }

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
            \Log::error('Fatal error in storeDataTPHKoordinat', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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
