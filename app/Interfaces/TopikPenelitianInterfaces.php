<?php

namespace App\Interfaces;

use App\Http\Requests\TopikPenelitianRequest;

interface TopikPenelitianInterfaces
{
    public function getAllData();
    public function createData(TopikPenelitianRequest $request);
    public function getDataById($id);
    public function updateData($id, TopikPenelitianRequest $request);
    public function deleteData($id);
}
