<?php

namespace App\Repositories;

use App\Http\Requests\PengajuanRequest;
use App\Interfaces\PengajuanInterfaces;
use App\Models\DetailPengajuan;
use App\Models\Pengajuan;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengajuanRepositories implements PengajuanInterfaces
{
    use HttpResponseTraits;

    protected $pengajuanModel;
    protected $itemPengajuanModel;

    public function __construct(Pengajuan $pengajuanModel, DetailPengajuan $itemPengajuanModel)
    {
        $this->pengajuanModel = $pengajuanModel;
        $this->itemPengajuanModel = $itemPengajuanModel;
    }

    public function getAllData()
    {
        $user = Auth::user();
        $query = $this->pengajuanModel->with(['mahasiswa', 'gelombang', 'pembimbing1', 'pembimbing2', 'detail_pengajuan.topik']);


        if ($user->role === 'mahasiswa') {
            $query->where('mahasiswa_id', $user->mahasiswa_id);
        }

        $data = $query->latest()->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(PengajuanRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->pengajuanModel->create([
                'mahasiswa_id'     => Auth::user()->mahasiswa_id,
                'gelombang_id'     => $request->gelombang_id,
                'harapan_judul'    => $request->harapan_judul,
                'alasan_prioritas' => $request->alasan_prioritas,
                'status_pengajuan' => 'pending',
            ]);

            foreach ($request->details as $item) {
                $this->itemPengajuanModel->create([
                    'pengajuan_id'   => $data->id,
                    'pilihan_judul'  => $item['pilihan_judul'],
                    'judul'          => $item['judul'],
                    'latar_belakang' => $item['latar_belakang'],
                    'topik_id'       => $item['topik_id'],
                ]);
            }

            DB::commit();
            return $this->success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $user = Auth::user();
        $data = $this->pengajuanModel->with(['mahasiswa', 'gelombang', 'pembimbing1', 'pembimbing2', 'detail_pengajuan.topik'])->find($id);

        if (!$data) {
            return $this->dataNotFound();
        }
        if ($user->role === 'mahasiswa' && $data->mahasiswa_id !== $user->mahasiswa_id) {
            return $this->error("Anda tidak memiliki akses ke data ini", 403);
        }

        return $this->success($data);
    }

    public function updateData($id, PengajuanRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->pengajuanModel->find($id);
            if (!$data) return $this->dataNotFound();

            // Mahasiswa hanya bisa update jika status masih pending/fixing
            if (Auth::user()->role === 'mahasiswa' && !in_array($data->status_pengajuan, ['pending', 'fixing'])) {
                return $this->error("Data sudah diproses dan tidak dapat diubah.", 403);
            }

            // Update Header
            $data->update([
                'gelombang_id'     => $request->gelombang_id,
                'harapan_judul'    => $request->harapan_judul,
                'alasan_prioritas' => $request->alasan_prioritas,
            ]);

            // Update Detail (Cara termudah: Hapus yang lama, simpan yang baru)
            $this->itemPengajuanModel->where('pengajuan_id', $id)->delete();
            foreach ($request->details as $item) {
                $this->itemPengajuanModel->create([
                    'pengajuan_id'   => $data->id,
                    'pilihan_judul'  => $item['pilihan_judul'],
                    'judul'          => $item['judul'],
                    'latar_belakang' => $item['latar_belakang'],
                    'topik_id'       => $item['topik_id'],
                ]);
            }

            DB::commit();
            return $this->success($data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function deleteData($id)
    {
        DB::beginTransaction();
        try {
            $data = $this->pengajuanModel->find($id);
            if (!$data) return $this->dataNotFound();

            $data->delete();

            DB::commit();
            return $this->delete();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
