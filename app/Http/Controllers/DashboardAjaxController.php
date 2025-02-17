<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\Wilayah;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Blok;
use Illuminate\Http\Request;
use App\Services\TPHService;

class DashboardAjaxController extends Controller
{
    protected $tphService;

    public function __construct(TPHService $tphService)
    {
        $this->tphService = $tphService;
    }

    public function index()
    {
        $user = check_previlege_cmp();
        // dd($user);
        return view('dashboard-ajax', [
            'canedit' => $user
        ]);
    }


    public function getPlotMap($afdelingId, Request $request)
    {
        $blokNama = $request->query('blokNama');
        return response()->json($this->tphService->getPlotMap($afdelingId, $blokNama));
    }

    public function getTPHCoordinates($estateId, $afdelingId, Request $request)
    {
        $blokNama = $request->query('blokNama');
        return response()->json($this->tphService->getTPHCoordinates($estateId, $afdelingId, $blokNama));
    }

    public function getLegendInfo($estateId, $afdelingId, Request $request)
    {
        $blokNama = $request->query('blokNama');
        return response()->json($this->tphService->getLegendInfo($estateId, $afdelingId, $blokNama));
    }

    public function updateTPH(Request $request, $id)
    {
        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update TPH data.'
            ], 403);
        }

        $request->validate([
            'tphNumber' => 'required|numeric|min:1',
            'ancakNumber' => 'required|numeric|min:1',
        ]);

        try {
            $this->tphService->updateTPH($id, $request->tphNumber, $request->ancakNumber);
            return response()->json(['success' => true, 'message' => 'TPH updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteTPH($id)
    {
        if (!check_previlege_cmp()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete TPH data.'
            ], 403);
        }

        try {
            $this->tphService->deleteTPH($id);
            return response()->json(['success' => true, 'message' => 'TPH status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
