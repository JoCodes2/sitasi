<?php

namespace App\Interfaces;

use App\Http\Requests\PengajuanRequest;

interface PengajuanInterfaces
{
    public function getAllData();
    public function createData(PengajuanRequest $request);
    public function getDataById($id);
    public function updateData($id, PengajuanRequest $request);
    public function deleteData($id);
}
