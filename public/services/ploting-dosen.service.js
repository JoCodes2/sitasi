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
                responsive: true
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

            if (res.code !== 200 || !Array.isArray(res.data)) return;

            let no = 1;

            res.data.forEach(item => {

                /* =============================
                 * 1. FILTER GELOMBANG AKTIF
                 * ============================= */
                const gel = item.gelombang;
                if (
                    !gel ||
                    !(
                        gel.is_aktif === 1 ||
                        gel.status === 'aktif'
                    )
                ) return;

                /* =============================
                 * 2. AMBIL JUDUL APPROVED
                 * ============================= */
                const detail =
                    item.indeks_judul_acc
                        ? item.detail_pengajuan?.[Number(item.indeks_judul_acc) - 1]
                        : item.detail_pengajuan?.find(
                            d => d.status_judul === 'approved'
                        );

                if (!detail) return;

                const mhs = item.user?.mahasiswa;

                /* =============================
                 * 3. RENDER KE DATATABLE
                 * ============================= */
                table.row.add([
                    `<div class="text-center fw-bold">${no++}</div>`,

                    /* Informasi Mahasiswa */
                    `
                <div>
                    <div class="fw-bold text-dark">
                        ${item.user?.nama ?? '-'}
                    </div>
                    <div class="small text-muted">
                        NIM : ${mhs?.nim ?? '-'}
                    </div>
                    <div class="small text-muted">
                        Angkatan : ${mhs?.angkatan ?? '-'}
                    </div>
                </div>
                `,

                    /* Informasi Judul */
                    `
                <div>
                    <div class="fw-semibold text-primary">
                        ${detail.judul}
                    </div>
                    <span class="badge bg-info mt-1">
                        ${detail.topik?.nama_topik ?? 'Tanpa Topik'}
                    </span>
                </div>
                `,

                    /* Informasi Gelombang */
                    `
                <div>
                    <div class="fw-bold">
                        Gelombang ${gel.gelombang_ke}
                    </div>
                    <div class="small text-muted">
                        ${gel.tahun_ajaran}
                    </div>
                    <span class="badge ${gel.semester === 'ganjil'
                        ? 'bg-primary'
                        : 'bg-warning'
                    }">
                        ${gel.semester}
                    </span>
                </div>
                `
                ]);
            });

            table.draw();

        } catch (err) {
            console.error('Gagal mengambil judul:', err);
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

    /* =====================================================
     *  EMPTY STATE
     * ===================================================== */
    renderEmptyMahasiswa() {
        const table = $('#mahasiswaTable').DataTable();
        table.clear().draw();

        $('#mahasiswaTable tbody').html(`
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                    Tidak ada judul <b>Approved</b> pada gelombang aktif
                </td>
            </tr>
        `);
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
}

export default PlottingService;
