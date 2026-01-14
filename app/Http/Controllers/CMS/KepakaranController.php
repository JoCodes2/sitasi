<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\KepakaranRequest;
use App\Repositories\KepakaranRepositories;
use Illuminate\Http\Request;

class KepakaranController extends Controller
{
    protected $Kepakaran;

    public function __construct(KepakaranRepositories $Kepakaran)
    {
        $this->Kepakaran = $Kepakaran;
    }
    public function getAllData()
    {
        return $this->Kepakaran->getAllData();
    }
    public function createData(KepakaranRequest $request)
    {
        return $this->Kepakaran->createData($request);
    }
    public function getDataById($id)
    {
        return $this->Kepakaran->getDataById($id);
    }

    public function updateData(KepakaranRequest $request, $id)
    {
        return $this->Kepakaran->updateData($id, $request);
    }
    public function deleteData($id)
    {
        return $this->Kepakaran->deleteData($id);
    }
}
