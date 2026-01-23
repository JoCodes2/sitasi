class PlottingService {

    ajaxRequest(url, method = 'GET', data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                success: res => resolve(res),
                error: err => reject(err)
            });
        });
    }

    /* =====================================================
     *  INIT DATATABLE
     * ===================================================== */
    initMahasiswaTable() {
        if (!$.fn.dataTable.isDataTable('#mahasiswaTable')) {
            $('#mahasiswaTable').DataTable({
                pageLength: 5,
                lengthChange: false,
                searching: false,
                ordering: false,
                info: true,
                responsive: true,
                // Tambahkan konfigurasi ini
                language: {
                    emptyTable: `
                    <div class="py-5 text-muted">
                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                        Tidak ada judul <b>Approved</b> pada gelombang aktif
                    </div>
                `,
                    zeroRecords: "Data tidak ditemukan"
                }
            });
        }
    }
    initDosenTable() {
        if (!$.fn.dataTable.isDataTable('#dosenTable')) {
            $('#dosenTable').DataTable({
                pageLength: 5,
                lengthChange: false,
                searching: true,
                ordering: false,
                info: true,
                responsive: true
            });
        }
    }





    /* =====================================================
     *  DATA JUDUL APPROVED (GELOMBANG AKTIF)
     * ===================================================== */
    async getJudulApproved() {
        this.initMahasiswaTable();
        const table = $('#mahasiswaTable').DataTable();
        table.clear();

        try {
            const res = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan`);

            if (!res.data || res.data.length === 0) {
                table.draw();
                return;
            }

            // 1. Cari Gelombang Terbaru (Tahun ajaran tertinggi, lalu Gelombang tertinggi)
            const latestGelombang = res.data
                .map(item => item.gelombang)
                .filter(g => g !== null)
                .sort((a, b) => {
                    if (b.tahun_ajaran !== a.tahun_ajaran) {
                        return b.tahun_ajaran.localeCompare(a.tahun_ajaran);
                    }
                    return b.gelombang_ke - a.gelombang_ke;
                })[0];

            if (!latestGelombang) {
                table.draw();
                return;
            }

            // 2. Filter data mahasiswa yang hanya berada di gelombang terbaru tersebut
            const filteredData = res.data.filter(item => item.gelombang?.id === latestGelombang.id);

            let no = 1;

            filteredData.forEach(item => {
                // 3. CARI JUDUL YANG APPROVED (Logika diperkuat)
                // Mencari di detail_pengajuan mana yang statusnya 'approved'
                const detail = item.detail_pengajuan?.find(d => d.status_judul === 'approved');

                // Jika tidak ada judul yang approved, lewati mahasiswa ini
                if (!detail) return;

                const mhs = item.user?.mahasiswa;
                const gel = item.gelombang;

                table.row.add([
                    `<div class="text-center">${no++}</div>`,
                    `<div>
                    <div class="fw-bold text-dark">${item.user?.nama ?? '-'}</div>
                    <small class="text-muted">${mhs?.nim ?? '-'} | Angkatan ${mhs?.angkatan ?? '-'}</small>
                </div>`,
                    `<div>
                    <div class="fw-bold text-primary">${detail.judul}</div>
                    <div class="d-flex gap-1 mt-1">
                        <small class="badge bg-info">${detail.topik?.nama_topik ?? '-'}</small>
                        <small class="badge bg-light-success text-success border border-success">Approved</small>
                    </div>
                </div>`,
                    `<div>
                    <span class="badge bg-light-primary text-primary">Gel ${gel.gelombang_ke}</span>
                    <div class="small text-muted">${gel.semester} ${gel.tahun_ajaran}</div>
                </div>`
                ]);
            });

            table.draw();

        } catch (err) {
            console.error('Gagal mengambil judul:', err);
            table.draw();
        }
    }





    /* =====================================================
     *  DATA DOSEN
     * ===================================================== */
    async getAllDosen() {
        this.initDosenTable();
        const table = $('#dosenTable').DataTable();
        table.clear();

        try {
            const res = await this.ajaxRequest(`${appUrl}/sitasi/dosen`);

            if (res.code !== 200 || !Array.isArray(res.data)) {
                this.renderEmptyDosen();
                return;
            }

            res.data.forEach((item, index) => {
                table.row.add([
                    `<div class="text-center">${index + 1}</div>`,

                    `<div class="fw-bold">${item.nama_lengkap} . ${item.gelar}</div>`,

                    `<span class="badge bg-secondary">
                        Kuota: ${item.kuota_max}
                     </span>`,

                    `<button
                        class="btn btn-sm btn-outline-success btn-select-dosen"
                        data-id="${item.id}">
                        Pilih
                     </button>`
                ]);
            });

            table.draw();

        } catch (err) {
            console.error('Gagal mengambil dosen:', err);
            this.renderEmptyDosen();
        }
    }



    renderEmptyDosen() {
        const table = $('#dosenTable').DataTable();
        table.clear().draw();

        $('#dosenTable tbody').html(`
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-user-xmark fa-2x mb-2"></i><br>
                    Data dosen belum tersedia
                </td>
            </tr>
        `);
    }
    constructor() {
        this.lastMatrixData = null; // Menyimpan cache data matriks
    }

    // Fungsi pembantu untuk Ajax
    async ajaxRequest(url, method = 'GET', data = null) {
        return await $.ajax({
            url: url,
            method: method,
            data: data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    }

    // Trigger Proses Plotting
    async triggerProsesPlotting() {
        const response = await this.ajaxRequest(`${appUrl}/sitasi/ploting`, 'GET');
        if (response.code === 200) {
            this.lastMatrixData = response.data.matriks;
        }
        return response;
    }



    // Render Tabel Utama (Tabel di bawah halaman)
    async renderHasilPlotting() {
        if (!$.fn.dataTable.isDataTable('#hasilTable')) {
            $('#hasilTable').DataTable({
                pageLength: 10,
                ordering: false,
                destroy: true
            });
        }
        const table = $('#hasilTable').DataTable();

        try {
            const res = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan`);

            const hasilData = res.data.filter(item => {
                // 1. Cek apakah sudah ada pembimbing
                const hasDosen = item.dosen_pembimbing_1_id && item.dosen_pembimbing_2_id;

                // 2. Cari detail yang pilihan_judul-nya sesuai dengan indeks_judul_acc
                // Ini memastikan meskipun yang di-acc pilihan 2 atau 3, datanya tetap ketemu
                const detail = item.detail_pengajuan?.find(d =>
                    Number(d.pilihan_judul) === Number(item.indeks_judul_acc)
                );

                // 3. Pastikan detail ditemukan dan statusnya approved
                return hasDosen && detail && detail.status_judul === 'approved';
            });

            table.clear();

            if (hasilData.length > 0) {
                $('#hasilPlottingContainer').removeClass('d-none');
                $('#totalMhsTerplot').text(`${hasilData.length} Terplot`);

                hasilData.forEach((item, index) => {
                    table.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        `<div>
                        <div class="fw-bold">${item.user?.nama || '-'}</div>
                        <small class="text-muted">${item.user?.mahasiswa?.nim || '-'}</small>
                    </div>`,
                        `<div class="text-primary fw-bold">${item.pembimbing1?.nama_lengkap || '-'}</div>`,
                        `<div class="text-success fw-bold">${item.pembimbing2?.nama_lengkap || '-'}</div>`
                    ]);
                });
            } else {
                $('#hasilPlottingContainer').addClass('d-none');
                $('#totalMhsTerplot').text(`0 Terplot`);
            }

            table.draw();
        } catch (err) {
            console.error("Gagal render hasil plotting:", err);
        }
    }
    // Tambahkan method ini di dalam class PlottingService Anda

    async triggerFinalisasi() {
        return await this.ajaxRequest(`${appUrl}/sitasi/ploting/finalisasi`, 'POST', {
        });
    }
}

export default PlottingService;
