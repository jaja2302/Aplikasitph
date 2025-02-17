<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KoordinatatTph;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TphManagementController extends Controller
{
    //

    public function dashboard()
    {
        return view('tph-management.dashboard');
    }

    public function GetTabel(Request $request)
    {
        $query = KoordinatatTph::query()
            ->select([
                'id',
                'regional',
                'dept_abbr as estate',
                'divisi_abbr as afdeling',
                'blok',
                'blok_kode as nama_blok',
                'nomor as tph',
                'ancak',
                'tahun',
                'status'
            ]);

        if ($request->regional_id) {
            $query->where('regional', $request->regional_id);
        }
        if ($request->estate_id) {
            $query->where('dept', $request->estate_id);
        }
        if ($request->afdeling_id) {
            $query->where('divisi', $request->afdeling_id);
        }
        if ($request->blok_id) {
            $query->where('blok', $request->blok_id);
        }

        // Get the per_page parameter from the request, default to 25 if not specified
        $perPage = $request->input('per_page', 25);

        // Validate that per_page is one of the allowed values
        $allowedPerPage = [25, 50, 100, 300];
        $perPage = in_array((int)$perPage, $allowedPerPage) ? (int)$perPage : 25;

        $data = $query->paginate($perPage);

        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ]
        ]);
    }

    public function delete(Request $request)
    {

        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete TPH data.'
            ], 403);
        }
        try {
            $tph = KoordinatatTph::find($request->id);
            // dd($tph);
            if (!$tph) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $tph->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }
    public function batchDelete(Request $request)
    {
        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete TPH data.'
            ], 403);
        }

        try {
            $ids = $request->ids;
            // dd($ids);
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No TPH selected for deletion'
                ], 400);
            }

            $deleted = KoordinatatTph::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus $deleted data TPH"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }

    public function store(Request $request)
    {

        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete TPH data.'
            ], 403);
        }

        try {
            $this->validateRequest($request);



            $check = $this->checkExistAndGetSampel($request, 'exist');
            // dd([
            //     'sampel' => $sampel,
            //     'request' => $request->all()
            // ]);
            if ($check) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan TPH: TPH sudah ada di database!'
                ], 500);
            }
            $sampel = $this->checkExistAndGetSampel($request, 'getData');
            $tph = $this->createNewTph($request, $sampel);
            $tph->save();
            return response()->json([
                'success' => true,
                'message' => 'TPH berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan TPH: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'regional_id' => 'required',
            'estate_id' => 'required',
            'afdeling_id' => 'required',
            'blok_id' => 'required',
            'nomor_tph' => 'required',
            'tahun_tanam' => 'required',
            'nomor_ancak' => 'required',
        ]);
    }

    private function checkExistAndGetSampel(Request $request, string $type)
    {
        $data = KoordinatatTph::where('regional', $request->regional_id)
            ->where('dept', $request->estate_id)
            ->where('divisi', $request->afdeling_id)
            ->where('blok', $request->blok_id);

        if ($type === 'exist') {
            $data->where('nomor', $request->nomor_tph)
                ->where('tahun', $request->tahun_tanam)
                ->where('ancak', $request->nomor_ancak);
        }

        return $data->first();
    }


    private function createNewTph(Request $request, KoordinatatTph $sampel)
    {

        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete TPH data.'
            ], 403);
        }
        $tph = new KoordinatatTph();

        // Basic information
        $tph->regional = $request->regional_id;
        $tph->dept = $request->estate_id;
        $tph->divisi = $request->afdeling_id;
        $tph->blok = $request->blok_id;
        $tph->nomor = $request->nomor_tph;
        // Company information
        $tph->company = $sampel->company;
        $tph->company_ppro = $sampel->company_ppro;
        $tph->company_abbr = $sampel->company_abbr;
        $tph->company_nama = $sampel->company_nama;
        // Department information
        $tph->dept_ppro = $sampel->dept_ppro;
        $tph->dept_abbr = $sampel->dept_abbr;
        $tph->dept_nama = $sampel->dept_nama;

        // Division information
        $tph->divisi_abbr = $sampel->divisi_abbr;
        $tph->divisi_ppro = $sampel->divisi_ppro;
        $tph->divisi_nama = $sampel->divisi_nama;

        // Block information
        $tph->blok_kode = $sampel->blok_kode;
        $tph->blok_nama = $sampel->blok_nama;
        $tph->ancak = $sampel->ancak;

        // Additional information
        $tph->kode_tph = "NULL";
        $tph->tahun = $sampel->tahun;
        $tph->lat = null;
        $tph->lon = null;
        // Meta information
        $tph->user_input = null;
        $tph->app_version = null;
        $tph->create_by = Auth::user()->id;
        $tph->create_date = now();
        $tph->create_nama = Auth::user()->nama;
        $tph->update_by = null;
        $tph->update_nama = 'NULL';
        $tph->update_date = "0000-00-00 00:00:00";
        $tph->history = "NULL";
        $tph->status = 0;

        return $tph;
    }
}
