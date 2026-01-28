<?php

namespace App\Interfaces;

use App\Http\Requests\PengajuanRequest;
use Illuminate\Http\Request;

interface PengajuanInterfaces
{
    public function getAllData();
    public function createData(PengajuanRequest $request);
    public function getDataById($id);
    public function updateData(PengajuanRequest $request, $id);
    public function deleteData($id);
    public function updateStatusJudul(Request $request, $id);

    public function getAllDataPengajuan();
}
