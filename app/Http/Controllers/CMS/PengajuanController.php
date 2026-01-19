<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengajuanRequest;
use App\Models\Pengajuan;
use App\Repositories\PengajuanRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    protected $pengajuanRepo;

    public function __construct(PengajuanRepositories $pengajuanRepo)
    {
        $this->pengajuanRepo = $pengajuanRepo;
    }
    public function getAllData()
    {
        return $this->pengajuanRepo->getAllData();
    }
    public function createData(PengajuanRequest $request)
    {
        return $this->pengajuanRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->pengajuanRepo->getDataById($id);
    }
    public function updateData(PengajuanRequest $request, $id)
    {
        return $this->pengajuanRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->pengajuanRepo->deleteData($id);
    }

    public function updateStatusJudul(Request $request, $id)
    {
        return $this->pengajuanRepo->updateStatusJudul($request, $id);
    }
}
