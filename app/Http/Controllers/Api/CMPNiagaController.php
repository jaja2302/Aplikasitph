<?php

namespace App\Http\Controllers\CMP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class CMPNiagaController extends Controller
{
    //convert
    public function proxyConvertDatasetToJson($urlType)
    {
        $url = 'https://cmp.citraborneo.co.id/tph/api/convert/' . $urlType;

        $response = Http::withHeaders([
            'X-API-Key' => 'CBICMP@2024',
            'Accept' => 'application/json'  // Tambahkan header Accept
        ])->get($url);

        // Debug response
        \Log::info('Response status: ' . $response->status());
        \Log::info('Response body: ' . $response->body());

        if ($response->successful()) {
            // Parse response body
            $responseData = $response->json();

            // Return raw response exactly as received
            return response()->json($responseData, 200, [
                'Content-Type' => 'application/json',
            ]);
        }

        return response()->json([
            'statusCode' => 0,
            'message' => 'Failed to process request',
            'error' => $response->body(),
        ], $response->status());
    }

    public function convertDatasetRegionalToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetRegionalToJson');
    }

    public function convertDatasetWilayahToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetWilayahToJson');
    }

    public function convertDatasetDeptToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetDeptToJson');
    }

    public function convertDatasetDivisiToJson(Request $request)
    {
        return $this->proxyConvertDatasetToJson('DatasetDivisiToJson');
    }

    public function convertDatasetBlokToJson(Request $request)
    {
        return $this->proxyConvertDatasetToJson('DatasetBlokToJson');
    }


    public function convertDatasetTPHNewToJson(Request $request)
    {
        return $this->proxyConvertDatasetToJson('DatasetTPHNewToJson');
    }

    public function convertDatasetKaryawanToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetKaryawanToJson');
    }

    public function convertDatasetKemandoranToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetKemandoranToJson');
    }

    public function convertDatasetKemandoranDetailToJson()
    {
        return $this->proxyConvertDatasetToJson('DatasetKemandoranDetailToJson');
    }



    // download 
    public function proxyDownloadDatasetJson($urlType)
    {
        $url = 'https://cmp.citraborneo.co.id/tph/api/download/' . $urlType;

        $response = Http::withHeaders([
            'X-API-Key' => 'CBICMP@2024',
        ])->get($url);

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Content-Disposition', $response->header('Content-Disposition'))
                ->header('Content-Encoding', $response->header('Content-Encoding'));
        }

        return response()->json([
            'statusCode' => 0,
            'message' => 'Failed to download file',
        ], $response->status());
    }


    public function downloadDatasetRegionalJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetRegionalJson');
    }

    public function downloadDatasetWilayahJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetWilayahJson');
    }

    public function downloadDatasetDeptJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetDeptJson');
    }

    public function downloadDatasetDivisiJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetDivisiJson');
    }

    public function downloadDatasetBlokJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetBlokJson');
    }

    public function downloadDatasetTPHNewJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetTPHNewJson');
    }

    public function downloadDatasetKaryawanJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetKaryawanJson');
    }

    public function downloadDatasetKemandoranJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetKemandoranJson');
    }

    public function downloadDatasetKemandoranDetailJson()
    {
        return $this->proxyDownloadDatasetJson('DatasetKemandoranDetailJson');
    }
}
