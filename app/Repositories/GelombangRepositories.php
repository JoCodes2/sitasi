<?php

namespace App\Repositories;

use App\Http\Requests\GelombangRequest;
use App\Interfaces\GelombangInterfaces;
use App\Models\Gelombang;
use App\Traits\HttpResponseTraits;

class GelombangRepositories implements GelombangInterfaces
{
    use HttpResponseTraits;
    protected $gelombangModel;
    public function __construct(Gelombang $gelombangModel)
    {
        $this->gelombangModel = $gelombangModel;
    }

    public function getAllData()
    {
        $data = $this->gelombangModel::all();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(GelombangRequest $request)
    {
        try {
            $data = new $this->gelombangModel;
            $data->semester = $request->input('semester');
            $data->tahun_ajaran = $request->input('tahun_ajaran');
            $data->gelombang_ke = $request->input('gelombang_ke');
            $data->tgl_mulai = $request->input('tgl_mulai');
            $data->tgl_selesai = $request->input('tgl_selesai');
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
        $data = $this->gelombangModel::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(GelombangRequest $request, $id)
    {
        try {
            $data = $this->gelombangModel::find($id);
            $data->semester = $request->input('semester');
            $data->tahun_ajaran = $request->input('tahun_ajaran');
            $data->gelombang_ke = $request->input('gelombang_ke');
            $data->tgl_mulai = $request->input('tgl_mulai');
            $data->tgl_selesai = $request->input('tgl_selesai');
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
        try {
            $data = $this->gelombangModel::find($id);
            $data->delete();
            return $this->delete();
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
}
