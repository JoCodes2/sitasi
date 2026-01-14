<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Repositories\MahasiswaRepositories;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected $mahasiswaRepo;

    public function __construct(MahasiswaRepositories $mahasiswaRepo)
    {
        $this->mahasiswaRepo = $mahasiswaRepo;
    }
    public function getAllData()
    {
        return $this->mahasiswaRepo->getAllData();
    }
    public function createData(MahasiswaRequest $request)
    {
        return $this->mahasiswaRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->mahasiswaRepo->getDataById($id);
    }
    public function updateData(MahasiswaRequest $request, $id)
    {
        return $this->mahasiswaRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->mahasiswaRepo->deleteData($id);
    }
}
