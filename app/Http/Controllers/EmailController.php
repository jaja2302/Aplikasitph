<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendReport;
use App\Models\KoordinatatTph;
use App\Models\Estate;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function sendReport(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'to' => 'required|array|min:1',
            'to.*' => 'required|email',
            'cc' => 'nullable|array',
            'cc.*' => 'email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email format',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get TPH data with progress statistics grouped by estate
        $tphStats = KoordinatatTph::select('dept_abbr')
            ->selectRaw('COUNT(*) as total_tph')
            ->selectRaw("SUM(CASE WHEN lon = '-' OR lon = '' OR lon is null OR status != 1 THEN 1 ELSE 0 END) as unverified_tph")
            ->selectRaw("SUM(CASE WHEN status = 1 AND lat != '-' AND lon != '-' THEN 1 ELSE 0 END) as verified_tph")
            ->groupBy('dept_abbr')
            ->get()
            ->mapWithKeys(function ($stat) {
                $progressPercentage = $stat->total_tph > 0
                    ? round(($stat->verified_tph / $stat->total_tph) * 100, 1)
                    : 0;

                return [$stat->dept_abbr => [
                    'total_tph' => $stat->total_tph,
                    'verified_tph' => $stat->verified_tph,
                    'unverified_tph' => $stat->unverified_tph,
                    'progress_percentage' => $progressPercentage
                ]];
            })
            ->toArray();

        // Group estates by regional with their TPH statistics
        $reportData = Wilayah::with(['estates' => function ($query) {
            $query->where('status', '=', '1')
                ->whereNotIn('abbr', ['GDE', 'BGE', 'SRE', 'LDE', 'BWE', 'MKE', 'PKE', 'BSE']);
        }])
            ->whereIn('regional', [1, 2, 3])
            ->get()
            ->groupBy('regional')
            ->map(function ($region) use ($tphStats) {
                return $region->pluck('estates')
                    ->flatten()
                    ->pluck('abbr')
                    ->flip()
                    ->map(function ($_, $estateCode) use ($tphStats) {
                        return $tphStats[$estateCode] ?? [
                            'total_tph' => 0,
                            'verified_tph' => 0,
                            'unverified_tph' => 0,
                            'progress_percentage' => 0
                        ];
                    })
                    ->filter()
                    ->toArray();
            })
            ->map(function ($estates) {
                // Add regional summary
                $regionalSummary = [
                    'total_tph' => array_sum(array_column($estates, 'total_tph')),
                    'verified_tph' => array_sum(array_column($estates, 'verified_tph')),
                    'unverified_tph' => array_sum(array_column($estates, 'unverified_tph')),
                ];
                $regionalSummary['progress_percentage'] = $regionalSummary['total_tph'] > 0
                    ? round(($regionalSummary['verified_tph'] / $regionalSummary['total_tph']) * 100, 1)
                    : 0;

                return [
                    'summary' => $regionalSummary,
                    'estates' => $estates
                ];
            })
            ->toArray();
        // dd($reportData);
        try {
            $mail = Mail::to($request->to);

            // Add CC recipients if provided
            if ($request->has('cc') && !empty($request->cc)) {
                $mail->cc($request->cc);
            }

            // Get latest update date
            $latestUpdate = KoordinatatTph::orderBy('update_date', 'DESC')
                ->value('update_date');

            $mail->send(new SendReport($reportData, $latestUpdate));

            return response()->json([
                'status' => 'success',
                'message' => 'Email report sent successfully',
                'data' => [
                    'to' => $request->to,
                    'cc' => $request->cc ?? [],
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
