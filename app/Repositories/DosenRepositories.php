<?php

namespace App\Repositories;

use App\Http\Requests\DosenRequest;
use App\Interfaces\DosenInterfaces;
use App\Models\Dosen;
use App\Traits\HttpResponseTraits;

class DosenRepositories implements DosenInterfaces
{
    use HttpResponseTraits;
    protected $Dosen;
    public function __construct(Dosen $Dosen)
    {
        $this->Dosen = $Dosen;
    }

    public function getAllData()
    {
        $data = $this->Dosen::all();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(DosenRequest $request)
    {
        try {
            $data = new $this->Dosen;
            $data->nidn = $request->input('nidn');
            $data->nama_lengkap = $request->input('nama_lengkap');
            $data->gelar = $request->input('gelar');
            $data->jabatan_fungsional = $request->input('jabatan_fungsional');
            $data->jabatan_struktural = $request->input('jabatan_struktural');
            $data->kuota_max = $request->input('kuota_max');
            $data->no_hp = $request->input('no_hp');
            $data->email = $request->input('email');
            $data->alamat = $request->input('alamat');
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
        $data = $this->Dosen::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData($id, DosenRequest $request)
    {
        try {
            $data = $this->Dosen::find($id);
            $data->nidn = $request->input('nidn');
            $data->nama_lengkap = $request->input('nama_lengkap');
            $data->gelar = $request->input('gelar');
            $data->jabatan_fungsional = $request->input('jabatan_fungsional');
            $data->jabatan_struktural = $request->input('jabatan_struktural');
            $data->kuota_max = $request->input('kuota_max');
            $data->no_hp = $request->input('no_hp');
            $data->email = $request->input('email');
            $data->alamat = $request->input('alamat');
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
        $data = $this->Dosen::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
