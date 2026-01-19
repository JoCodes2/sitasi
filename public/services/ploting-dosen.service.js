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
        table.clear().draw();

        try {
            const res = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan`);


            const latestGelombang = res.data
                .map(item => item.gelombang)
                .filter(g => g !== null)
                .sort((a, b) => {
                    if (b.tahun_ajaran !== a.tahun_ajaran) {
                        return b.tahun_ajaran.localeCompare(a.tahun_ajaran);
                    }
                    return b.gelombang_ke - a.gelombang_ke;
                })[0];

            const filteredData = res.data
                .filter(item => item.gelombang?.id === latestGelombang.id)
                .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));


            let hasApprovedData = false;
            let no = 1;

            filteredData.forEach(item => {
                let detail = null;
                if (item.indeks_judul_acc !== null) {
                    detail = item.detail_pengajuan?.[Number(item.indeks_judul_acc) - 1];
                } else {
                    detail = item.detail_pengajuan?.find(d => d.status_judul === 'approved');
                }

                if (!detail) return;

                hasApprovedData = true;
                const mhs = item.user?.mahasiswa;
                const gel = item.gelombang;

                table.row.add([
                    `<div class="text-center">${no++}</div>`,
                    `<div>
                    <div class="fw-bold">${item.user?.nama ?? '-'}</div>
                    <small class="text-muted">${mhs?.nim ?? '-'} | ${mhs?.angkatan ?? '-'}</small>
                </div>`,
                    `<div>
                    <div class="fw-bold text-primary">${detail.judul}</div>
                    <div class="d-flex gap-1 mt-1">
                        <small class="badge bg-info">${detail.topik?.nama_topik ?? '-'}</small>
                    </div>
                </div>`,
                    `<div>
                    <span class="badge bg-light-primary text-primary">Gel ${gel.gelombang_ke}</span>
                    <div class="small">${gel.semester} ${gel.tahun_ajaran}</div>
                </div>`
                ]);
            });

            if (!hasApprovedData) {
                this.renderEmptyMahasiswa();
            } else {
                table.draw();
            }

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
