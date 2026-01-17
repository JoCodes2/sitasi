<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterfaces;
use App\Models\User;
use App\Traits\HttpResponseTraits;

class UserRepositories implements UserInterfaces
{
    use HttpResponseTraits;
    protected $User;
    public function __construct(User $User)
    {
        $this->User = $User;
    }

    public function getAllData()
    {
        $data = $this->User::all();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(UserRequest $request)
    {
        try {
            $data = new $this->User;
            $data->nama = $request->input('nama');
            $data->email = $request->input('email');
            $data->password = bcrypt($request->input('password'));
            $data->role = $request->input('role');
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
        $data = $this->User::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData($id, UserRequest $request)
    {
        try {
            $data = $this->User::find($id);
            $data->nama = $request->input('nama');
            $data->email = $request->input('email');
            $data->password = bcrypt($request->input('password'));

            // if ($request->filled('password')) {
            //     $data->password = bcrypt($request->input('password'));
            // }
            $data->role = $request->input('role');
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
        $data = $this->User::find($id);
        if (!$data) {
            return $this->dataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
