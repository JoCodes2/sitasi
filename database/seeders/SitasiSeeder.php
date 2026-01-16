<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TopikPenelitian;
use App\Models\Dosen;
use App\Models\Kepakaran;
use Illuminate\Support\Str;

class SitasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Topik Penelitian
        $topiks = [
            'Web Development',
            'Jaringan Komputer',
            'Data Mining',
            'Sistem Pendukung Keputusan (SPK)',
            'GIS (Geographic Information System)',
            'Sistem Pakar',
            'Desain Grafis & UI/UX',
            'Mobile Development'
        ];

        $topikIds = [];
        foreach ($topiks as $item) {
            // Pastikan ID dikonversi ke string
            $id = (string) Str::uuid();
            TopikPenelitian::create([
                'id' => $id,
                'nama_topik' => $item
            ]);
            $topikIds[] = $id;
        }

        // 2. Data Dosen
        $dosenData = [
            ['nama' => 'Dr. Ahmad Fauzi', 'gelar' => 'S.Kom., M.T.'],
            ['nama' => 'Siti Aminah', 'gelar' => 'S.T., M.Cs.'],
            ['nama' => 'Budi Santoso', 'gelar' => 'S.Kom., M.Kom.'],
            ['nama' => 'Ir. Diana Putri', 'gelar' => 'M.TI.'],
            ['nama' => 'Eko Prasetyo', 'gelar' => 'S.Kom., M.T.'],
            ['nama' => 'Fadhil Muhammad', 'gelar' => 'M.Kom.'],
            ['nama' => 'Gita Permata', 'gelar' => 'S.T., M.Kom.'],
            ['nama' => 'Hendra Wijaya', 'gelar' => 'S.Kom., M.Cs.'],
            ['nama' => 'Indah Lestari', 'gelar' => 'M.T.'],
            ['nama' => 'Joko Susilo', 'gelar' => 'S.T., M.TI.'],
        ];

        foreach ($dosenData as $index => $data) {
            $dosenId = (string) Str::uuid();
            Dosen::create([
                'id' => $dosenId,
                'nidn' => '092801' . (10 + $index),
                'nama_lengkap' => $data['nama'],
                'gelar' => $data['gelar'],
                'jabatan_fungsional' => 'Lektor',
                'jabatan_struktural' => ($index == 0) ? 'Ketua Program Studi' : null,
                'kuota_max' => 5,
                'no_hp' => '0812345678' . $index,
                'email' => strtolower(str_replace([' ', '.'], '', $data['nama'])) . '@stmik.ac.id',
                'alamat' => 'Jl. Pendidikan No. ' . ($index + 1) . ' Kota Palu',
            ]);

            // 3. Seed Kepakaran (Loop SEMUA topik)
            // Memperbaiki array_flip dengan memastikan data di dalam array adalah string
            $keahlianUtama = (array) array_rand(array_flip($topikIds), 2);

            foreach ($topikIds as $tId) {
                $persentase = 0;

                if (in_array($tId, $keahlianUtama)) {
                    $persentase = rand(70, 100);
                } else {
                    // Pengetahuan dasar (Peluang 30%)
                    $isSideSkill = rand(1, 10) <= 3;
                    $persentase = $isSideSkill ? rand(10, 40) : 0;
                }

                Kepakaran::create([
                    'id' => (string) Str::uuid(),
                    'dosen_id' => $dosenId,
                    'topik_id' => $tId,
                    'persentase' => $persentase,
                ]);
            }
        }
    }
}
