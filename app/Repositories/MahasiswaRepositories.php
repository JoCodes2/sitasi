<?php

namespace App\Repositories;

use App\Http\Requests\MahasiswaRequest;
use App\Interfaces\MahasiswaInterfaces;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaRepositories implements MahasiswaInterfaces
{
    use HttpResponseTraits;
    protected $userModel;
    protected $mahasiswaModel;
    public function __construct(User $userModel, Mahasiswa $mahasiswaModel)
    {
        $this->userModel = $userModel;
        $this->mahasiswaModel = $mahasiswaModel;
    }
    public function getAllData()
    {
        $data = $this->mahasiswaModel::with('user')->get();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function createData(MahasiswaRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userModel->create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'mahasiswa',
            ]);

            $mahasiswa = $this->mahasiswaModel->create([
                'user_id'       => $user->id,
                'nim'           => $request->nim,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama'         => $request->agama,
                'alamat'        => $request->alamat,
                'prodi'         => $request->prodi,
                'angkatan'      => $request->angkatan,
            ]);

            DB::commit();

            return $this->success([
                'user' => $user,
                'mahasiswa' => $mahasiswa
            ]);
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
        $data = $this->mahasiswaModel::with('user')->where('id', $id)->first();
        if (!$data) {
            return $this->dataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(MahasiswaRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->mahasiswaModel->find($id);

            if (!$data) {
                return $this->dataNotFound();
            }

            $user = $this->userModel->find($data->user_id);

            $userData = [
                'name'  => $request->nama,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            $data->update([
                'nim'           => $request->nim,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama'         => $request->agama,
                'alamat'        => $request->alamat,
                'prodi'         => $request->prodi,
                'angkatan'      => $request->angkatan,
            ]);

            DB::commit();

            return $this->success($data);
        } catch (\Exception $th) {
            DB::rollBack();
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
        DB::beginTransaction();
        try {
            $mahasiswa = $this->mahasiswaModel->find($id);

            if (!$mahasiswa) {
                return $this->dataNotFound();
            }

            $userId = $mahasiswa->user_id;

            $mahasiswa->delete();

            $user = $this->userModel->find($userId);
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return $this->delete();
        } catch (\Exception $th) {
            DB::rollBack();
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
