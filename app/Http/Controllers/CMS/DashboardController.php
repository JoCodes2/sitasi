<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Gelombang;
use App\Models\Kepakaran;
use App\Models\Pengajuan;
use App\Models\TopikPenelitian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $gelombang = Gelombang::where('is_aktif', true)->first();
        $totalMhs = $gelombang ? Pengajuan::where('gelombang_id', $gelombang->id)->count() : 0;
        $totalKuota = Dosen::sum('kuota_max');

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_dosen' => Dosen::count(),
                    'total_mhs' => $totalMhs,
                    'gelombang' => $gelombang?->tahun_ajaran . ' - ' . $gelombang?->semester,
                ],
                'readiness' => [
                    'dosen_input_kepakaran' => Kepakaran::distinct('dosen_id')->count(),
                    'ratio_status' => ($totalKuota >= $totalMhs) ? 'Cukup' : 'Overload'
                ],
                'topik_stats' => TopikPenelitian::withCount('detail_pengajuan')->get(),
                // ... data lainnya
            ]
        ]);
    }
}
