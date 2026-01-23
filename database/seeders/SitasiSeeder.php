<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TopikPenelitian;
use App\Models\Dosen;
use App\Models\Kepakaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SitasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED TOPIK PENELITIAN
        $topiks = [
            'WEB' => 'WEB',
            'SPK' => 'SPK',
            'IOT' => 'IOT',
        ];

        $topikIds = [];
        foreach ($topiks as $key => $val) {
            $id = (string) Str::uuid();
            $topikIds[$key] = $id;
            DB::table('topik_penelitian')->insert([
                'id' => $id,
                'nama_topik' => $val,
                'created_at' => now(),
            ]);
        }

        // 2. SEED DOSEN
        $dosenData = [
            ['nama' => 'Wildan', 'gelar' => 'M.T.', 'nidn' => '001', 'pakar' => [
                'WEB' => 50,
                'SPK' => 40,
                'IOT' => 80,
            ]],
            ['nama' => 'Ali', 'gelar' => 'M.Kom.', 'nidn' => '002', 'pakar' => [
                'WEB' => 100,
                'SPK' => 60,
                'IOT' => 70,
            ]],
            ['nama' => 'Sukardi', 'gelar' => 'Dr.', 'nidn' => '003', 'pakar' => [
                'WEB' => 40,
                'SPK' => 80,
                'IOT' => 50,
            ]],
        ];

        foreach ($dosenData as $d) {
            $dosenId = (string) Str::uuid();
            DB::table('dosen')->insert([
                'id' => $dosenId,
                'nidn' => $d['nidn'],
                'nama_lengkap' => $d['nama'],
                'gelar' => $d['gelar'],
                'kuota_max' => 5,
                'no_hp' => '0812345678',
                'email' => strtolower($d['nama']) . '@univ.ac.id',
                'alamat' => 'Kampus',
                'created_at' => now(),
            ]);

            // Seed Kepakaran (Persentase)
            foreach ($d['pakar'] as $topikKey => $skor) {
                DB::table('kepakaran')->insert([
                    'id' => (string) Str::uuid(),
                    'dosen_id' => $dosenId,
                    'topik_id' => $topikIds[$topikKey],
                    'persentase' => $skor,
                ]);
            }
        }

        // 3. SEED GELOMBANG
        $gelId = (string) Str::uuid();
        DB::table('gelombang')->insert([
            'id' => $gelId,
            'semester' => 'ganjil',
            'tahun_ajaran' => '2026/2027',
            'gelombang_ke' => 1,
            'tgl_mulai' => now(),
            'tgl_selesai' => now()->addDays(10),
            'is_aktif' => true,
        ]);

        // 4. SEED 5 MAHASISWA & PENGAJUAN (SUDAH APPROVE)
        $mhsData = [
            ['nama' => 'Mhs IOT', 'nim' => '101', 'topik' => 'IOT'],
            ['nama' => 'Mhs Web 1', 'nim' => '102', 'topik' => 'WEB'],
            ['nama' => 'Mhs SPK', 'nim' => '103', 'topik' => 'SPK'],
            ['nama' => 'Mhs Web 2', 'nim' => '104', 'topik' => 'WEB'],
            ['nama' => 'Mhs Web 3', 'nim' => '105', 'topik' => 'WEB'],
        ];

        foreach ($mhsData as $m) {
            $userId = (string) Str::uuid();
            DB::table('users')->insert([
                'id' => $userId,
                'nama' => $m['nama'],
                'email' => $m['nim'] . '@student.ac.id',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            DB::table('mahasiswa')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'nim' => $m['nim'],
                'tempat_lahir' => 'Kota',
                'tanggal_lahir' => '2000-01-01',
                'jenis_kelamin' => 'L',
                'agama' => 'islam',
                'alamat' => 'Alamat',
                'no_hp' => '0899',
                'prodi' => 'TI',
                'angkatan' => 2022,
            ]);

            $pengajuanId = (string) Str::uuid();
            DB::table('pengajuan')->insert([
                'id' => $pengajuanId,
                'user_id' => $userId,
                'gelombang_id' => $gelId,
                'harapan_judul' => '1',
                'alasan_prioritas' => 'Testing Hungarian',
                'indeks_judul_acc' => 1, // Judul pertama yang di-acc
                'created_at' => now(),
            ]);

            // Detail Judul dengan status 'approved'
            DB::table('detail_pengajuan')->insert([
                'id' => (string) Str::uuid(),
                'pengajuan_id' => $pengajuanId,
                'pilihan_judul' => '1',
                'judul' => 'Judul Penelitian ' . $m['topik'] . ' - ' . $m['nama'],
                'latar_belakang' => 'Latar belakang...',
                'topik_id' => $topikIds[$m['topik']],
                'status_judul' => 'approved',
                'created_at' => now(),
            ]);
        }
    }
}
