<?php

namespace App\Repositories;

use App\Http\Requests\TopikPenelitianRequest;
use App\Interfaces\TopikPenelitianInterfaces;
use App\Models\TopikPenelitian;
use App\Traits\HttpResponseTraits;

class TopikPenelitianRepositories implements TopikPenelitianInterfaces
{
    use HttpResponseTraits;
    protected $topikPenelitian;
    public function __construct(TopikPenelitian $topikPenelitian)
    {
        $this->topikPenelitian = $topikPenelitian;
    }

    public function getAllData()
    {
        $data = $this->topikPenelitian::all();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(TopikPenelitianRequest $request)
    {
        try {
            $data = new $this->topikPenelitian;
            $data->nama_topik = $request->input('nama_topik');
            $data->save();
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }
    public function getDataById($id)
    {
        $data = $this->topikPenelitian::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData($id, TopikPenelitianRequest $request)
    {
        try {
            $data = $this->topikPenelitian::find($id);
            $data->nama_topik = $request->input('nama_topik');
            $data->save();
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }
    public function deleteData($id)
    {
        $data = $this->topikPenelitian::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
