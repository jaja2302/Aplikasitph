<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Regional;
use App\Models\TPH\BUnitCode;
use App\Models\TPH\CompanyCode;
use App\Models\TPH\DivisionCode;
use App\Models\TPH\FieldCode;
use App\Models\TPH\TPH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\TPH\TPHNew;
use App\Models\TPH\Dept;
use App\Models\TPH\Divisi;
use App\Models\TPH\Blok;
use App\Models\TPH\Company;
use Illuminate\Support\Facades\Log;  // Import Log facade
use App\Models\TPH\Wilayah;
use Illuminate\Support\Facades\Validator;
use App\Models\TPH\VersioningDB;
use App\Models\CMP\Karyawan;
use App\Models\CMP\Kemandoran;
use App\Models\CMP\KemandoranDetail;

class CMPController extends Controller
{
    public function convertDatasetToJson($modelClass, $columns, $fileName, $dataKey, $query = null)
    {
        try {
            // Retrieve the table name dynamically from the model class
            $tableName = (new $modelClass)->getTable(); // Get table name from model

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

            // Save the compressed data to a .gz file
            Storage::put($fileName . '.zip', $compressedData);

            VersioningDB::updateOrCreate(
                ['table_name' => $tableName],
                ['date_modified' => $currentDateTime]
            );

            // Return the download link for the file
            return response()->json([
                'statusCode' => 1,
                'message' => 'Dataset saved as a minified JSON gzip file successfully.',
                'download_link' => url('storage/' . $fileName . '.zip'),
            ]);
        } catch (\Exception $e) {
            // Return error JSON response
            return response()->json([
                'statusCode' => 0,
                'message' => 'An error occurred while saving the dataset.',
                'error' => $e->getMessage(),
            ]);
        }
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
        return $this->convertDatasetToJson(Dept::class, $columns, 'datasetDept', 'DeptDB');
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
        $query = Divisi::query();
        if ($est) {
            // Convert 'est' to uppercase and find the matching department
            $abbr = strtoupper($est);
            $dept = Dept::where('abbr', $abbr)->first();

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

        return $this->convertDatasetToJson(Divisi::class, $columns, $fileName, 'DivisiDB', $query);
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
            $dept = Dept::where('abbr', $abbr)->first();
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
        $query = TPHNew::query();

        if ($est) {
            $abbr = strtoupper($est);
            $dept = Dept::where('abbr', $abbr)->first();

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

        return $this->convertDatasetToJson(TPHNew::class, $columns, $fileName, 'TPHDB', $query);
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
            // Define the file path in storage
            $filePath = storage_path('app/' . $fileName);

            // Check if the file exists
            if (!file_exists($filePath)) {
                return response()->json([
                    'statusCode' => 0,
                    'message' => 'File not found: ' . $fileName
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
                Dept::class,
                Divisi::class,
                Blok::class,
                TPHNew::class
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

            // Save the Base64 encoded, compressed data to a .txt file (without .json.gz part)
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
