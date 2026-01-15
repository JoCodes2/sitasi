<?php

namespace App\Interfaces;

use App\Http\Requests\MahasiswaRequest;

interface MahasiswaInterfaces
{
    public function getAllData();
    public function createData(MahasiswaRequest $request);
    public function getDataById($id);
    public function updateData(MahasiswaRequest $request, $id);
    public function deleteData($id);
}
