<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopikPenelitianRequest;
use App\Repositories\TopikPenelitianRepositories;
use Illuminate\Http\Request;

class TopikPenelitianController extends Controller
{
    protected $topikPenelitian;

    public function __construct(TopikPenelitianRepositories $topikPenelitian)
    {
        $this->topikPenelitian = $topikPenelitian;
    }
    public function getAllData()
    {
        return $this->topikPenelitian->getAllData();
    }
    public function createData(TopikPenelitianRequest $request)
    {
        return $this->topikPenelitian->createData($request);
    }
    public function getDataById($id)
    {
        return $this->topikPenelitian->getDataById($id);
    }

    public function updateData(TopikPenelitianRequest $request, $id)
    {
        return $this->topikPenelitian->updateData($id, $request);
    }

    public function deleteData($id)
    {
        return $this->topikPenelitian->deleteData($id);
    }
}
