<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\DosenRequest;
use App\Repositories\DosenRepositories;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    protected $Dosen;

    public function __construct(DosenRepositories $Dosen)
    {
        $this->Dosen = $Dosen;
    }
    public function getAllData()
    {
        return $this->Dosen->getAllData();
    }
    public function createData(DosenRequest $request)
    {
        return $this->Dosen->createData($request);
    }
    public function getDataById($id)
    {
        return $this->Dosen->getDataById($id);
    }

    public function updateData(DosenRequest $request, $id)
    {
        return $this->Dosen->updateData($id, $request);
    }

    public function deleteData($id)
    {
        return $this->Dosen->deleteData($id);
    }
}
