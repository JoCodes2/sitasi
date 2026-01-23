<?php

namespace App\Services;

use App\Models\Dosen;
use App\Models\Gelombang;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\DB;

class PlotingService
{
    public function runAutoPloting()
    {
        return DB::transaction(function () {
            // 1. Ambil Gelombang Aktif
            $gelombang = Gelombang::where('is_aktif', true)->first()
                ?? Gelombang::orderBy('created_at', 'desc')->first();

            // 2. Ambil Pengajuan yang siap diplot
            $daftarPengajuan = Pengajuan::where('gelombang_id', $gelombang->id)
                ->whereNotNull('indeks_judul_acc')
                ->whereNull('dosen_pembimbing_1_id')
                ->with(['detail_pengajuan.topik', 'user.mahasiswa'])
                ->get();

            if ($daftarPengajuan->isEmpty()) {
                return ['sukses' => 0, 'pesan' => 'Tidak ada pengajuan untuk diproses.'];
            }

            // 3. Ambil Master Data Dosen
            $dosenList = Dosen::with('kepakaran')->get();

            // Filter Khusus Dosen Lektor untuk Pembimbing 1
            $dosenLektor = Dosen::where('jabatan_fungsional', 'Lektor')->get();

            if ($dosenLektor->isEmpty()) {
                return ['sukses' => 0, 'pesan' => 'Gagal: Tidak ditemukan dosen dengan jabatan fungsional Lektor.'];
            }

            // 4. Proses Hungarian untuk Pembimbing 2 (Kepakaran)
            $hungarianData = $this->calculateHungarian($daftarPengajuan, $dosenList);
            $assignment = $hungarianData['assignments'];
            $matrixVisual = $hungarianData['visual'];

            $counter = 0;
            foreach ($daftarPengajuan as $index => $pengajuan) {
                // --- PENENTUAN P2 (Hasil Hungarian) ---
                $p2Id = $assignment[$index] ?? null;
                $pengajuan->dosen_pembimbing_2_id = $p2Id;

                // --- PENENTUAN P1 (Wajib Lektor & Tidak boleh sama dengan P2) ---
                // Cari kandidat lektor yang ID-nya bukan P2
                $kandidatP1 = $dosenLektor->where('id', '!=', $p2Id);

                // Jika kandidat lain ada, pilih secara acak. Jika tidak ada (lektor sangat terbatas), terpaksa ambil yang tersedia.
                $selectedP1 = ($kandidatP1->isNotEmpty()) ? $kandidatP1->random() : $dosenLektor->random();
                $pengajuan->dosen_pembimbing_1_id = $selectedP1->id;

                $pengajuan->tgl_plotting = now();
                $pengajuan->save();
                $counter++;
            }

            // 5. Refresh data untuk mendapatkan Nama Dosen melalui Relasi Model
            $daftarPengajuan->load(['pembimbing1', 'pembimbing2']);

            // 6. Siapkan Data Final untuk Modal Perhitungan
            $matrixVisual['final_results'] = $daftarPengajuan->map(function ($p) {
                return [
                    'mhs' => $p->user->nama ?? 'N/A',
                    'judul' => $p->detail_pengajuan->where('pilihan_judul', $p->indeks_judul_acc)->first()->judul ?? '-',
                    'p1' => $p->pembimbing1->nama_lengkap . " (Lektor)",
                    'p2' => $p->pembimbing2->nama_lengkap ?? 'N/A'
                ];
            });

            return [
                'sukses' => $counter,
                'pesan' => 'Plotting Hungarian Berhasil.',
                'matriks' => $matrixVisual
            ];
        });
    }

    private function calculateHungarian($mahasiswa, $dosen)
    {
        $matrixCost = [];
        $dosenSlots = [];
        $dosenNames = [];

        // 1. Expand Dosen berdasarkan Kuota (Column Expansion)
        foreach ($dosen as $d) {
            for ($i = 1; $i <= $d->kuota_max; $i++) {
                $dosenSlots[] = [
                    'id' => $d->id,
                    'kepakaran' => $d->kepakaran->pluck('persentase', 'topik_id')->toArray()
                ];
                $dosenNames[] = $d->nama_lengkap . " (Slot $i)";
            }
        }

        // 2. Bangun Cost Matrix (Rows: Mahasiswa, Cols: Dosen Slots)
        foreach ($mahasiswa as $mIndex => $m) {
            $judulAcc = $m->detail_pengajuan->where('pilihan_judul', $m->indeks_judul_acc)->first();
            $topikId = $judulAcc->topik_id ?? null;

            foreach ($dosenSlots as $dIndex => $slot) {
                $skorKepakaran = $slot['kepakaran'][$topikId] ?? 0;
                // Cost = 100 - Skor (Mencari nilai minimum)
                $matrixCost[$mIndex][$dIndex] = 100 - $skorKepakaran;
            }
        }

        // 3. Eksekusi Penugasan
        $result = $this->solveHungarian($matrixCost, $dosenSlots);

        return [
            'assignments' => $result['assignments'],
            'visual' => [
                'mahasiswa' => $mahasiswa->map(fn($m) => [
                    'nama' => $m->user->nama,
                    'judul' => $m->detail_pengajuan->where('pilihan_judul', $m->indeks_judul_acc)->first()->judul ?? '-'
                ]),
                'dosen_names' => $dosenNames,
                'matrix_hungarian' => $matrixCost,
                'selected_indexes' => $result['selected_indexes'],
            ]
        ];
    }

    private function solveHungarian($matrix, $dosenSlots)
    {
        $assignments = [];
        $selectedIndexes = [];
        $usedSlots = [];

        foreach ($matrix as $mIdx => $costs) {
            $rowCosts = $costs;
            asort($rowCosts); // Urutkan biaya terkecil

            foreach ($rowCosts as $dIdx => $cost) {
                // Pastikan slot belum dipakai mahasiswa lain
                if (!in_array($dIdx, $usedSlots)) {
                    $assignments[$mIdx] = $dosenSlots[$dIdx]['id'];
                    $selectedIndexes[$mIdx] = $dIdx;
                    $usedSlots[] = $dIdx;
                    break;
                }
            }
        }

        return [
            'assignments' => $assignments,
            'selected_indexes' => $selectedIndexes
        ];
    }
}
