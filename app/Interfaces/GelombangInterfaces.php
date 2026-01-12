<?php

namespace App\Interfaces;

use App\Http\Requests\GelombangRequest;

interface GelombangInterfaces
{
    public function getAllData();
    public function createData(GelombangRequest $request);
    public function getDataById($id);
    public function updateData(GelombangRequest $request, $id);
    public function deleteData($id);
}
