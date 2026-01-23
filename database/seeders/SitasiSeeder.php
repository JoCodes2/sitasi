<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SitasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED TOPIK PENELITIAN
        $topiks = ['WEB' => 'WEB', 'SPK' => 'SPK', 'IOT' => 'IOT'];
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

        // 2. SEED DOSEN (5 Dosen, Kuota Total 75)
        $dosenData = [
            ['nama' => 'Wildan', 'gelar' => 'M.T.', 'nidn' => '001', 'pakar' => ['WEB' => 50, 'SPK' => 40, 'IOT' => 80]],
            ['nama' => 'Ali', 'gelar' => 'M.Kom.', 'nidn' => '002', 'pakar' => ['WEB' => 100, 'SPK' => 60, 'IOT' => 70]],
            ['nama' => 'Sukardi', 'gelar' => 'Dr.', 'nidn' => '003', 'pakar' => ['WEB' => 40, 'SPK' => 80, 'IOT' => 50]],
            ['nama' => 'Budi', 'gelar' => 'M.Cs.', 'nidn' => '004', 'pakar' => ['WEB' => 70, 'SPK' => 30, 'IOT' => 90]],
            ['nama' => 'Ratna', 'gelar' => 'M.T.', 'nidn' => '005', 'pakar' => ['WEB' => 30, 'SPK' => 90, 'IOT' => 40]],
        ];

        foreach ($dosenData as $d) {
            $dosenId = (string) Str::uuid();
            DB::table('dosen')->insert([
                'id' => $dosenId,
                'nidn' => $d['nidn'],
                'nama_lengkap' => $d['nama'],
                'gelar' => $d['gelar'],
                'kuota_max' => 15,
                'no_hp' => '0812' . rand(1000, 9999),
                'email' => strtolower($d['nama']) . '@univ.ac.id',
                'alamat' => 'Kampus Terpadu Blok A',
                'created_at' => now(),
            ]);

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

        // 4. SEED 30 MAHASISWA & 3 JUDUL PER MAHASISWA
        $agamas = ['islam', 'hindu', 'kristen', 'budha', 'konghucu'];
        $prodis = ['TI', 'SI'];
        $topikKeys = array_keys($topiks);

        for ($i = 1; $i <= 30; $i++) {
            $userId = (string) Str::uuid();
            $nim = "2022000" . str_pad($i, 2, '0', STR_PAD_LEFT);

            DB::table('users')->insert([
                'id' => $userId,
                'nama' => "Mahasiswa Test $i",
                'email' => "mhs$i@student.ac.id",
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            DB::table('mahasiswa')->insert([
                'id' => (string) Str::uuid(),
                'user_id' => $userId,
                'nim' => $nim,
                'tempat_lahir' => 'Kota ' . rand(1, 5),
                'tanggal_lahir' => '2001-' . rand(1, 12) . '-' . rand(1, 28),
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'agama' => $agamas[array_rand($agamas)],
                'alamat' => "Jl. Mahasiswa Nomor $i, Cluster Testing",
                'no_hp' => '0896' . rand(10000000, 99999999),
                'prodi' => $prodis[array_rand($prodis)],
                'angkatan' => 2022,
                'created_at' => now(),
            ]);

            $pengajuanId = (string) Str::uuid();
            DB::table('pengajuan')->insert([
                'id' => $pengajuanId,
                'user_id' => $userId,
                'gelombang_id' => $gelId,
                'harapan_judul' => '1',
                'alasan_prioritas' => 'Testing Hungarian Algorithm dengan 30 data sampel.',
                'indeks_judul_acc' => null,
                'created_at' => now(),
            ]);

            // SEED 3 JUDUL PER MAHASISWA
            for ($j = 1; $j <= 3; $j++) {
                $randomTopik = $topikKeys[array_rand($topikKeys)];

                DB::table('detail_pengajuan')->insert([
                    'id' => (string) Str::uuid(),
                    'pengajuan_id' => $pengajuanId,
                    'pilihan_judul' => $j,
                    'judul' => "Rancang Bangun Sistem " . $randomTopik . " Versi $j.$i",
                    'latar_belakang' => "Latar belakang detail untuk pengajuan judul ke-$j mahasiswa $i.",
                    'topik_id' => $topikIds[$randomTopik],
                    'status_judul' => 'pending',
                    'created_at' => now(),
                ]);
            }
        }
    }
}
