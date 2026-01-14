<?php

namespace App\Repositories;

use App\Http\Requests\KepakaranRequest;
use App\Interfaces\KepakaranInterfaces;
use App\Models\Kepakaran;
use App\Traits\HttpResponseTraits;

class KepakaranRepositories implements KepakaranInterfaces
{
    use HttpResponseTraits;
    protected $Kepakaran;
    public function __construct(Kepakaran $Kepakaran)
    {
        $this->Kepakaran = $Kepakaran;
    }

    public function getAllData()
    {
        $data = Kepakaran::with(['dosen', 'topik'])->get();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(KepakaranRequest $request)
    {
        try {
            $data = new $this->Kepakaran;
            $data->dosen_id = $request->input('dosen_id');
            $data->topik_id = $request->input('topik_id');
            $data->persentase = $request->input('persentase');

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
        $data = $this->Kepakaran::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData($id, KepakaranRequest $request)
    {
        try {
            $data = $this->Kepakaran::find($id);
            $data->dosen_id = $request->input('dosen_id');
            $data->topik_id = $request->input('topik_id');
            $data->persentase = $request->input('persentase');

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
        $data = $this->Kepakaran::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
