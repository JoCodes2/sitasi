<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\GelombangRequest;
use App\Repositories\GelombangRepositories;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    protected $gelombangRepo;

    public function __construct(GelombangRepositories $gelombangRepo)
    {
        $this->gelombangRepo = $gelombangRepo;
    }
    public function getAllData()
    {
        return $this->gelombangRepo->getAllData();
    }
    public function createData(GelombangRequest $request)
    {
        return $this->gelombangRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->gelombangRepo->getDataById($id);
    }
    public function updateData(GelombangRequest $request, $id)
    {
        return $this->gelombangRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->gelombangRepo->deleteData($id);
    }
}
