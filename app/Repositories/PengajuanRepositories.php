<?php

namespace App\Repositories;

use App\Http\Requests\PengajuanRequest;
use App\Interfaces\PengajuanInterfaces;
use App\Models\DetailPengajuan;
use App\Models\Pengajuan;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
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
        $query = $this->pengajuanModel->with(['user.mahasiswa', 'gelombang', 'pembimbing1', 'pembimbing2', 'detail_pengajuan.topik']);


        if ($user->role == 'mahasiswa') {
            $query->where('user_id', $user->id);
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
                'user_id'     => Auth::user()->id,
                'gelombang_id'     => $request->gelombang_id,
                'harapan_judul'    => $request->harapan_judul,
                'alasan_prioritas' => $request->alasan_prioritas,
            ]);

            foreach ($request->details as $item) {
                $this->itemPengajuanModel->create([
                    'pengajuan_id'   => $data->id,
                    'pilihan_judul'  => $item['pilihan_judul'],
                    'judul'          => $item['judul'],
                    'latar_belakang' => $item['latar_belakang'],
                    'topik_id'       => $item['topik_id'],
                    'status_judul'   => 'pending',
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
        $data = $this->pengajuanModel->with(['user.mahasiswa', 'gelombang', 'pembimbing1', 'pembimbing2', 'detail_pengajuan.topik'])->find($id);

        if (!$data) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function updateData(PengajuanRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->pengajuanModel->find($id);
            if (!$data) return $this->dataNotFound();

            if (Auth::user()->role === 'mahasiswa' && !in_array($data->status_pengajuan, ['pending'])) {
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
                    'status_judul'   => 'pending',
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

    public function updateStatusJudul(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $detail = $this->itemPengajuanModel::with('pengajuan')->findOrFail($id);
            $pengajuan = $detail->pengajuan;

            $detail->update([
                'status_judul' => $request->status
            ]);

            if ($request->status === 'approved') {
                $this->itemPengajuanModel::where('pengajuan_id', $pengajuan->id)
                    ->where('id', '!=', $detail->id)
                    ->update([
                        'status_judul' => 'rejected'
                    ]);

                $pengajuan->update([
                    'indeks_judul_acc' => $detail->pilihan_judul,
                ]);
            }

            DB::commit();

            return $this->success([
                'detail_pengajuan' => $detail,
                'pengajuan' => $pengajuan
            ], 'Status judul berhasil diperbarui');
        } catch (\Throwable $th) {
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
