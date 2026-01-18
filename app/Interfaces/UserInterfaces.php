<?php

namespace App\Interfaces;

use App\Http\Requests\UserRequest;

interface UserInterfaces
{
    public function getAllData();
    public function createData(UserRequest $request);
    public function getDataById($id);
    public function updateData($id, UserRequest $request);
    public function deleteData($id);
}
