<?php

namespace App\Interfaces;

use App\Http\Requests\KepakaranRequest;

interface KepakaranInterfaces
{
    public function getAllData();
    public function createData(KepakaranRequest $request);
    public function getDataById($id);
    public function updateData($id, KepakaranRequest $request);
    public function deleteData($id);
}
