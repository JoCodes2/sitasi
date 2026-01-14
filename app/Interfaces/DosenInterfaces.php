<?php

namespace App\Interfaces;

use App\Http\Requests\DosenRequest;

interface DosenInterfaces
{
    public function getAllData();
    public function createData(DosenRequest $request);
    public function getDataById($id);
    public function updateData($id, DosenRequest $request);
    public function deleteData($id);
}
