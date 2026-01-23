<?php

namespace App\Repositories;

use App\Interfaces\PlotingInterfaces;
use App\Models\DetailPengajuan;
use App\Models\Pengajuan;
use App\Services\PlotingService;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlotingRepositories implements PlotingInterfaces
{
    use HttpResponseTraits;

    protected $plotingService;
    protected $pengajuanModel;

    public function __construct(PlotingService $plotingService, Pengajuan $pengajuanModel)
    {
        $this->plotingService = $plotingService;
        $this->pengajuanModel = $pengajuanModel;
    }
    public function getAllFinalisasi()
    {
        $user = Auth::user();

        $query = $this->pengajuanModel::with(['user.mahasiswa', 'gelombang', 'pembimbing1', 'pembimbing2', 'detail_pengajuan.topik']);

        $query->whereHas('detail_pengajuan', function ($q) {
            $q->whereIn('status_judul', ['finalisasi', 'published']);
        });

        if ($user->role == 'mahasiswa') {
            $query->where('user_id', $user->id);
        }

        $data = $query->latest()->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }
    public function getDataById($id)
    {
        $data = $this->pengajuanModel->with([
            'user.mahasiswa',
            'pembimbing1',
            'pembimbing2',
            'detail_pengajuan' => function ($q) {
                $q->whereIn('status_judul', ['finalisasi', 'published']);
            }
        ])->find($id);

        if (!$data) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function updateData(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            try {
                $pengajuan = $this->pengajuanModel->find($id);

                if (!$pengajuan) {
                    return $this->dataNotFound();
                }

                $pengajuan->update([
                    'dosen_pembimbing_1_id' => $request->dosen_pembimbing_1_id,
                    'dosen_pembimbing_2_id' => $request->dosen_pembimbing_2_id,
                    'tgl_plotting'          => now(),
                ]);

                $pengajuan->detail_pengajuan()
                    ->whereIn('status_judul', ['finalisasi', 'published'])
                    ->update(['status_judul' => 'published']);

                return $this->success(
                    $pengajuan->load(['pembimbing1', 'pembimbing2']),
                    "Data pembimbing berhasil diperbarui dan status telah dipublikasikan."
                );
            } catch (\Throwable $th) {
                return $this->error(
                    $th->getMessage(),
                    400,
                    $th,
                    class_basename($this),
                    __FUNCTION__
                );
            }
        });
    }

    public function plotingDosen()
    {
        try {
            $checkApproved = DetailPengajuan::where('status_judul', 'approved')
                ->whereHas('pengajuan', function ($query) {
                    $query->whereNull('dosen_pembimbing_1_id')
                        ->orWhereNull('dosen_pembimbing_2_id');
                })->exists();

            if (!$checkApproved) {
                return $this->error(
                    "Tidak ada pengajuan mahasiswa dengan status 'Approved' yang tersedia untuk di-plotting.",
                    400
                );
            }
            $result = $this->plotingService->runAutoPloting();

            $pesan = $result['pesan'] ?? 'Berhasil melakukan plotting';

            return $this->success(
                $result,
                $pesan
            );
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
    public function finalisasi(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $pengajuanAktif = Pengajuan::whereNotNull('dosen_pembimbing_1_id')
                    ->whereNotNull('dosen_pembimbing_2_id')
                    ->whereNotNull('indeks_judul_acc')
                    ->get();

                if ($pengajuanAktif->isEmpty()) {
                    return $this->error("Tidak ada data plotting yang siap difinalisasi.", 404);
                }

                foreach ($pengajuanAktif as $pengajuan) {

                    DetailPengajuan::where('pengajuan_id', $pengajuan->id)
                        ->where('pilihan_judul', (string)$pengajuan->indeks_judul_acc)
                        ->update(['status_judul' => 'finalisasi']);

                    DetailPengajuan::where('pengajuan_id', $pengajuan->id)
                        ->where('pilihan_judul', '!=', (string)$pengajuan->indeks_judul_acc)
                        ->delete();
                }

                return $this->success(
                    null,
                    "Berhasil memfinalisasi " . $pengajuanAktif->count() . " data pengajuan. Judul lainnya telah dihapus."
                );
            } catch (\Throwable $th) {
                return $this->error(
                    $th->getMessage(),
                    400,
                    $th,
                    class_basename($this),
                    __FUNCTION__
                );
            }
        });
    }
}
