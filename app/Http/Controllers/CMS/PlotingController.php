<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Gelombang;
use App\Models\Pengajuan;
use App\Repositories\PlotingRepositories;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlotingController extends Controller
{
    use HttpResponseTraits;
    protected $plotingRepo;
    public function __construct(PlotingRepositories $plotingRepo)
    {
        $this->plotingRepo = $plotingRepo;
    }
    public function plotingDosen()
    {
        return $this->plotingRepo->plotingDosen();
    }
    public function finalisasi(Request $request)
    {
        return $this->plotingRepo->finalisasi($request);
    }
    public function getAllFinalisasi()
    {
        return $this->plotingRepo->getAllFinalisasi();
    }
}
