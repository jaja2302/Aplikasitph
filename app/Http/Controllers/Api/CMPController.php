<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Afdeling;
use Illuminate\Http\Request;
use App\Models\BUnitCode;
use App\Models\CompanyCode;
use App\Models\DivisionCode;
use App\Models\FieldCode;
use App\Models\TPH;
use Illuminate\Support\Facades\Storage;
use App\Models\CMP\StagingWorker;
use App\Models\CMP\StagingWorkerGroup;
use App\Models\CMP\StagingWorkerInGroup;
use App\Models\Regional;
// use App\Models\Dept;
// use App\Models\Divisi;
use App\Models\Blok;
use Illuminate\Support\Facades\Log;  // Import Log facade
use App\Models\Wilayah;
use Illuminate\Support\Facades\Validator;
use App\Models\VersioningDB;
use App\Models\CMP\Karyawan;
use App\Models\CMP\Kemandoran;
use App\Models\CMP\KemandoranDetail;
use App\Models\Estate;
use App\Models\KoordinatatTph;
use Illuminate\Support\Facades\Http;

class CMPController extends Controller
{


    public function convertDatasetToJson($modelClass, $columns, $fileName, $dataKey, $query = null)
    {
        try {
            // Retrieve the table name dynamically from the model class
            $tableName = (new $modelClass)->getTable(); // Get table name from model

            // If $query is null, initialize it with a query builder instance
            if (!$query) {
                $query = $modelClass::query();
            }

            // Initialize an empty array to store the mapped data
            $allData = [];

            // Process data in chunks to handle large datasets
            $query->chunk(1000, function ($chunk) use (&$allData, $columns) {
                // Map each chunk and append to the main data array
                $chunkData = $chunk->map(function ($item) use ($columns) {
                    return collect($columns)->mapWithKeys(function ($columnName, $key) use ($item) {
                        return [$key => $item[$columnName]];
                    });
                });
                $allData = array_merge($allData, $chunkData->toArray());
            });

            $currentDateTime = now()->toDateTimeString();

            // Prepare the final data structure
            $finalData = [
                'date_modified' => $currentDateTime, // One timestamp for all
                'key' => $columns,                   // Use the manually defined columns
                $dataKey => $allData,                // Data fetched from the model and mapped to keys
            ];

            // Convert data to JSON and minify it (without unnecessary whitespaces)
            $jsonData = json_encode($finalData);

            print_r($jsonData);

            // Gzip compress the data
            $compressedData = gzencode($jsonData);

            // Save the compressed data to a .zip file
            Storage::disk('local')->put($fileName . '.zip', $compressedData);
            // Pastikan API menerima request dengan format benar
            $apiResponse = Http::timeout(30)->post('https://tph.srs-ssms.com/api/VersioningDB', [
                'table_name' => $tableName
            ]);

            // Cek apakah request berhasil
            if ($apiResponse->failed()) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'Failed to update VersioningDB API',
                    'error' => $apiResponse->body(),
                ], $apiResponse->status());
            }


            return response()->json([
                'statusCode' => 1,
                'message' => 'Dataset saved as a minified JSON zip file successfully.',
                'download_link' => url('storage/' . $fileName . '.zip'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while saving the dataset.',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function convertDatasetCompanyToJson()
    {
        $columns = [
            "1" => "CompanyCode",
            "2" => "CompanyName",
        ];
        return $this->convertDatasetToJson(CompanyCode::class, $columns, 'datasetCompanyCode', 'CompanyCodeDB');
    }

    public function convertDatasetBUnitCodeToJson()
    {
        $columns = [
            "1" => "BUnitCode",
            "2" => "BUnitName",
            "3" => "CompanyCode",
        ];
        return $this->convertDatasetToJson(BUnitCode::class, $columns, 'datasetBUnitCode', 'BUnitCodeDB');
    }

    public function convertDatasetDivisionCodeToJson()
    {
        $columns = [
            "1" => "DivisionCode",
            "2" => "DivisionName",
            "3" => "BUnitCode",
        ];
        return $this->convertDatasetToJson(DivisionCode::class, $columns, 'datasetDivisionCode', 'DivisionCodeDB');
    }

    public function convertDatasetFieldCodeToJson()
    {
        $columns = [
            "1" => "FieldCode",
            "2" => "BUnitCode",
            "3" => "DivisionCode",
            "4" => "FieldName",
            "5" => "FieldNumber",
            "6" => "FieldLandArea",
            "7" => "PlantingYear",
            "8" => "InitialNoOfPlants",
            "9" => "PlantsPerHectare",
            "10" => "IsMatured",
        ];
        return $this->convertDatasetToJson(FieldCode::class, $columns, 'datasetFieldCode', 'FieldCodeDB');
    }

    public function convertDatasetTPHCodeToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "CompanyCode",
            "3" => "Regional",
            "4" => "BUnitCode",
            "5" => "DivisionCode",
            "6" => "FieldCode",
            "7" => "planting_year",
            "8" => "ancak",
            "9" => "tph",
        ];
        return $this->convertDatasetToJson(TPH::class, $columns, 'datasetTPHCode', 'TPHDB');
    }



    public function convertDatasetWorkerInGroupToJson()
    {
        $columns = [
            "1" => "worker_group_code",
            "2" => "worker_code",
            "3" => "estate_code",
        ];
        return $this->convertDatasetToJson(StagingWorkerInGroup::class, $columns, 'datasetWorkerInGroup', 'WorkerInGroupDB');
    }

    public function convertDatasetWorkerGroupToJson()
    {
        $columns = [
            "1" => "worker_group_code",
            "2" => "code",
            "3" => "name",
            "4" => "estate_code",
            "5" => "group_type",
            "6" => "company_code",
            "7" => "estate_code",
        ];
        return $this->convertDatasetToJson(StagingWorkerGroup::class, $columns, 'datasetWorkerGroup', 'WorkerGroupDB');
    }

    public function convertDatasetWorkerToJson()
    {
        $columns = [
            "1" => "worker_code",
            "2" => "code",
            "3" => "name",
            "4" => "company_code",
            "5" => "estate_code",
        ];
        return $this->convertDatasetToJson(StagingWorker::class, $columns, 'datasetWorker', 'WorkerDB');
    }


    public function convertDatasetRegionalToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "abbr",
            "3" => "nama",
        ];
        return $this->convertDatasetToJson(Regional::class, $columns, 'datasetRegional', 'RegionalDB');
    }

    public function convertDatasetWilayahToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "regional",
            "3" => "abbr",
            "4" => "nama",
            "5" => "status",
        ];
        return $this->convertDatasetToJson(Wilayah::class, $columns, 'datasetWilayah', 'WilayahDB');
    }

    public function convertDatasetDeptToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "id_ppro",
            "3" => "regional",
            "4" => "wilayah",
            "5" => "company",
            "6" => "abbr",
            "7" => "nama",
        ];
        return $this->convertDatasetToJson(Estate::class, $columns, 'datasetDept', 'DeptDB');
    }



    public function convertDatasetDivisiToJson(Request $request)
    {
        // Define the columns
        $columns = [
            "1" => "id",
            "2" => "id_ppro",
            "3" => "company",
            "4" => "dept",
            "5" => "abbr",
            "6" => "nama",
        ];

        // Check if the 'est' parameter is provided
        $est = $request->input('est');

        // If 'est' is provided, filter the query; otherwise, fetch all data
        $query = Afdeling::query();
        if ($est) {
            // Convert 'est' to uppercase and find the matching department
            $abbr = strtoupper($est);
            $dept = Estate::where('abbr', $abbr)->first();

            // If no department is found, return an error response
            if (!$dept) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'No department found for the given abbreviation.',
                ]);
            }

            // Filter by the department ID
            $query->where('dept', $dept->id);

            // Append the 'est' abbreviation to the file name
            $fileName = 'datasetDivisi_' . $abbr;
        } else {
            $fileName = 'datasetDivisi';
        }

        return $this->convertDatasetToJson(Afdeling::class, $columns, $fileName, 'DivisiDB', $query);
    }

    public function convertDatasetBlokToJson(Request $request)
    {
        $columns = [
            "1" => "id",
            "2" => "regional",
            "3" => "company",
            "4" => "company_ppro",
            "5" => "dept",
            "6" => "dept_ppro",
            "7" => "dept_abbr",
            "8" => "divisi",
            "9" => "divisi_ppro",
            "10" => "nama",
            "11" => "kode",
            "12" => "ancak",
            "13" => "jumlah_tph",
            "14" => "tahun",
        ];

        $est = $request->input('est');
        $query = Blok::query();

        if ($est) {
            $abbr = strtoupper($est);
            $dept = Estate::where('abbr', $abbr)->first();
            if (!$dept) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'No department found for the given abbreviation.',
                ]);
            }
            $query->where('dept_abbr', $dept->id);
            $fileName = 'datasetBlok_' . $abbr;
        } else {
            $fileName = 'datasetBlok';
        }

        return $this->convertDatasetToJson(Blok::class, $columns, $fileName, 'BlokDB', $query);
    }


    public function convertDatasetTPHNewToJson(Request $request)
    {
        $columns = [
            "1" => "id",
            "2" => "regional",
            "3" => "company",
            // "4" => "company_ppro",
            "4" => "dept",
            "5" => "dept_abbr",
            "6" => "divisi",
            // "8" => "divisi_ppro",
            "7" => "blok",
            "8" => "blok_nama",
            "9" => "ancak",
            "10" => "nomor",
            "11" => "tahun",
            "12" => "status",
            "13" => "user_input",
            "14" => "lat",
            "15" => "lon",
            "16" => "update_date",
        ];

        $est = $request->input('est');

        // Use query builder instead of all()
        $query = KoordinatatTph::query();

        if ($est) {
            $abbr = strtoupper($est);
            $dept = Estate::where('abbr', $abbr)->first();

            if (!$dept) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'No department found for the given abbreviation.',
                ]);
            }

            Log::info("Found department: " . $dept->id);

            $query->where('dept_abbr', $dept->id);
            $fileName = 'datasetTPH_' . $abbr;
        } else {
            $fileName = 'datasetTPH';
        }

        return $this->convertDatasetToJson(KoordinatatTph::class, $columns, $fileName, 'TPHDB', $query);
    }

    public function convertDatasetKaryawanToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "company",
            "3" => "company_ppro",
            "4" => "company_abbr",
            "5" => "company_nama",
            "6" => "dept",
            "7" => "dept_ppro",
            "8" => "dept_abbr",
            "9" => "dept_nama",
            "10" => "divisi",
            "11" => "divisi_ppro",
            "12" => "divisi_abbr",
            "13" => "divisi_nama",
            "14" => "nik",
            "15" => "nama",
            "16" => "jabatan",
            "17" => "jenis_kelamin",
            "18" => "tmk",
            "19" => "tgl_resign",
            "20" => "create_by",
            "21" => "create_nama",
            "22" => "create_date",
            "23" => "update_by",
            "24" => "update_nama",
            "25" => "update_date",
            "26" => "history",
            "27" => "status",
        ];
        return $this->convertDatasetToJson(Karyawan::class, $columns, 'datasetKaryawan', 'KaryawanDB');
    }

    public function convertDatasetKemandoranToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "company",
            "3" => "company_ppro",
            "4" => "company_abbr",
            "5" => "company_nama",
            "6" => "dept",
            "7" => "dept_ppro",
            "8" => "dept_abbr",
            "9" => "dept_nama",
            "10" => "divisi",
            "11" => "divisi_ppro",
            "12" => "divisi_abbr",
            "13" => "divisi_nama",
            "14" => "kode",
            "15" => "nama",
            "16" => "type",
            "17" => "create_by",
            "18" => "create_nama",
            "19" => "create_date",
            "20" => "update_by",
            "21" => "update_nama",
            "22" => "update_date",
            "23" => "history",
            "24" => "status",
        ];
        return $this->convertDatasetToJson(Kemandoran::class, $columns, 'datasetKemandoran', 'KemandoranDB');
    }

    public function convertDatasetKemandoranDetailToJson()
    {
        $columns = [
            "1" => "id",
            "2" => "kode_kemandoran",
            "3" => "header",
            "4" => "kid",
            "5" => "nik",
            "6" => "nama",
            "7" => "tgl_mulai",
            "8" => "tgl_akhir",
            "9" => "status",
        ];
        return $this->convertDatasetToJson(KemandoranDetail::class, $columns, 'datasetKemandoranDetail', 'KemandoranDetailDB');
    }

    public function downloadDatasetJson($fileName)
    {
        try {
            // Define the file path using Storage disk 'local'
            $filePath = Storage::disk('local')->path($fileName);

            // Check if the file exists
            if (!file_exists($filePath)) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'File not found: ' . $fileName
                ], 404);
            }

            // Return the file for download using response()->download()
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while downloading the file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function downloadDatasetCompanyJson()
    {
        return $this->downloadDatasetJson('datasetCompanyCode.zip');
    }

    public function downloadDatasetBUnitJson()
    {
        return $this->downloadDatasetJson('datasetBUnitCode.zip');
    }

    public function downloadDatasetDivisionJson()
    {
        return $this->downloadDatasetJson('datasetDivisionCode.zip');
    }

    public function downloadDatasetTPHJson()
    {
        return $this->downloadDatasetJson('datasetTPHCode.zip');
    }

    public function downloadDatasetFieldJson()
    {
        return $this->downloadDatasetJson('datasetFieldCode.zip');
    }

    public function downloadDatasetWorkerInGroupJson()
    {
        return $this->downloadDatasetJson('datasetWorkerInGroup.zip');
    }

    public function downloadDatasetWorkerGroupJson()
    {
        return $this->downloadDatasetJson('datasetWorkerGroup.zip');
    }

    public function downloadDatasetWorkerJson()
    {
        return $this->downloadDatasetJson('datasetWorker.zip');
    }

    public function downloadDatasetRegionalJson()
    {
        return $this->downloadDatasetJson('datasetRegional.zip');
    }

    public function downloadDatasetWilayahJson()
    {
        return $this->downloadDatasetJson('datasetWilayah.zip');
    }

    public function downloadDatasetDeptJson()
    {
        return $this->downloadDatasetJson('datasetDept.zip');
    }

    public function downloadDatasetDivisiJson()
    {
        return $this->downloadDatasetJson('datasetDivisi.zip');
    }

    public function downloadDatasetBlokJson()
    {
        return $this->downloadDatasetJson('datasetBlok.zip');
    }

    public function downloadDatasetTPHNewJson()
    {
        return $this->downloadDatasetJson('datasetTPH.zip');
    }

    public function downloadDatasetKaryawanJson()
    {
        return $this->downloadDatasetJson('datasetKaryawan.zip');
    }

    public function downloadDatasetKemandoranJson()
    {
        return $this->downloadDatasetJson('datasetKemandoran.zip');
    }

    public function downloadDatasetKemandoranDetailJson()
    {
        return $this->downloadDatasetJson('datasetKemandoranDetail.zip');
    }


    public function getTablesLatestModified()
    {
        try {
            // Array of model classes
            $models = [
                Regional::class,
                Wilayah::class,
                Estate::class,
                Afdeling::class,
                Blok::class,
                KoordinatatTph::class
            ];

            // Get table names from models
            $tableData = [];
            foreach ($models as $modelClass) {
                $tableObject = new $modelClass;
                $tableName = $tableObject->getTable();

                $versionInfo = VersioningDB::where('table_name', $tableName)->first();

                $tableData[$tableName] = $versionInfo ? $versionInfo->date_modified : null;
            }

            return response()->json([
                'statusCode' => 1,
                'message' => 'Data retrieved successfully.',
                'data' => $tableData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while retrieving the data.',
                'error' => $e->getMessage()
            ]);
        }
    }



    public function convertDatasetTPHtoJson()
    {
        try {
            // Fetch data from each table
            $companyData = CompanyCode::all();
            $bUnitData = BUnitCode::all();
            $divisionData = DivisionCode::all();
            $fieldData = FieldCode::all();
            $tphData = TPH::all();

            // Merge all data into a single array
            $allData = [
                'CompanyCode' => $companyData,
                'BUnitCode' => $bUnitData,
                'DivisionCode' => $divisionData,
                'FieldCode' => $fieldData,
                'TPH' => $tphData
            ];

            // Convert data to JSON
            $jsonData = json_encode($allData);

            // Compress the JSON data using GZIP
            $compressedData = gzencode($jsonData);

            // Encode the compressed data into Base64
            $base64EncodedData = base64_encode($compressedData);

            // Save the Base64 encoded, compressed data to a .txt file (without .json.zip part)
            $fileName = 'dataset_tph.txt';  // Only use .txt extension
            Storage::put($fileName, $base64EncodedData);

            // Return the download link for the file
            return response()->json([
                'statusCode' => 1,
                'message' => 'Data compressed and encoded successfully.',
                'download_link' => url('storage/' . $fileName),
            ]);
        } catch (\Exception $e) {
            // Return error JSON response
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while fetching the data.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function downloadEncryptedDatasetTPHJson()
    {
        try {
            // Define the file name and its path in storage
            $fileName = 'dataset_tph.txt';
            $filePath = storage_path('app/' . $fileName);

            // Check if the file exists
            if (!file_exists($filePath)) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'File not found.'
                ], 404);
            }

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while downloading the file.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function compareJsonTxtWithDb()
    {


        try {
            // Define the file name and path
            $fileName = 'dataset_tph.txt';
            $filePath = storage_path('app/' . $fileName);

            // Check if the file exists
            print_r("whaatt");
            if (!file_exists($filePath)) {

                print_r("nais");
                // Trigger the function to create the file
                $this->convertDatasetTPHtoJson();

                // Wait for the file to be created
                sleep(2); // Wait for 2 seconds to ensure the file is created

                // Check again if the file exists
                if (!file_exists($filePath)) {
                    return response()->json([
                        'statusCode' => 0,
                        'message' => 'File not found after attempting to create it.',
                    ], 404);
                }
            }

            // Read the file content and decode the Base64-encoded, gzipped JSON
            $base64EncodedData = file_get_contents($filePath);
            $compressedData = base64_decode($base64EncodedData);
            $fileDataJson = gzdecode($compressedData);

            // Decode JSON into an associative array
            $fileData = json_decode($fileDataJson, true);


            print_r($fileData);

            // // Query database data
            // $companyData = CompanyCode::all()->toArray();
            // $bUnitData = BUnitCode::all()->toArray();
            // $divisionData = DivisionCode::all()->toArray();
            // $fieldData = FieldCode::all()->toArray();
            // $tphData = TPH::all()->toArray();

            // $dbData = [
            //     'CompanyCode' => $companyData,
            //     'BUnitCode' => $bUnitData,
            //     'DivisionCode' => $divisionData,
            //     'FieldCode' => $fieldData,
            //     'TPH' => $tphData,
            // ];

            // print_r($companyData);
            // print_r($bUnitData);
            // print_r($divisionData);
            // print_r($fieldData);
            // print_r($tphData);

            // Compare database data with file data
            // if ($fileData != $dbData) {
            //     // If data is different, update the version in VersionDBTPH
            //     $versionRecord = VersionDBTPH::first();
            //     if ($versionRecord) {
            //         $versionRecord->version += 1;
            //         $versionRecord->save();
            //     } else {
            //         return response()->json([
            //             'statusCode' => 0,
            //             'message' => 'Version record not found in VersionDBTPH.',
            //         ], 404);
            //     }

            //     return response()->json([
            //         'statusCode' => 1,
            //         'message' => 'Data mismatch. Version updated successfully.',
            //         'new_version' => $versionRecord->version,
            //     ]);
            // }

            // return response()->json([
            //     'statusCode' => 1,
            //     'message' => 'Data matches. No version update required.',
            // ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred during comparison.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
