class FinalisasiPlotingService {
    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                // Hilangkan processData & contentType jika hanya GET biasa agar tidak error
                processData: method === 'GET' ? true : false,
                contentType: method === 'GET' ? 'application/x-www-form-urlencoded' : false,
                success: (response) => resolve(response),
                error: (error) => reject(error),
            });
        });
    }
    initFinalisasiTable() {
        if (!$.fn.dataTable.isDataTable('#finalisasiTable')) {
            $('#finalisasiTable').DataTable({
                pageLength: 10,
                lengthChange: true,
                searching: true,
                ordering: false,
                info: true,
                responsive: true,
                language: {
                    // Konfigurasi agar saat data kosong, desain kustom Anda yang muncul
                    emptyTable: `
                    <div class="py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fa-solid fa-file-circle-exclamation fa-4x text-light-primary mb-3" style="opacity: 0.5;"></i>
                            <h5 class="fw-bold text-secondary">Data Tidak Ditemukan</h5>
                        </div>
                    </div>
                `,
                }
            });
        }
    }

    async getAllData() {
        this.initFinalisasiTable(); // Pastikan tabel terinisialisasi
        const table = $('#finalisasiTable').DataTable();

        try {
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/ploting/get-finalisasi`, 'GET');

            table.clear();

            if (responseData.code === 200 && responseData.data && responseData.data.length > 0) {
                responseData.data.forEach((item, index) => {
                    const detailAcc = item.detail_pengajuan?.find(d =>
                        ['finalisasi', 'published'].includes(d.status_judul)
                    );

                    const isPublished = detailAcc?.status_judul === 'published';
                    const statusBadge = isPublished
                        ? `<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Resmi</span>`
                        : `<span class="badge bg-light-warning text-warning border border-warning"><i class="fas fa-clock me-1"></i> Draft</span>`;

                    const editBtn = isPublished
                        ? `<button class="btn btn-secondary btn-sm" disabled><i class="fa fa-lock"></i></button>`
                        : `<button class="btn btn-warning btn-sm btnEdit" data-id="${item.id}"><i class="fa fa-edit text-white"></i></button>`;

                    table.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        `<div><b>${item.user?.nama}</b><br><small class="text-muted">${item.user?.mahasiswa?.nim}</small></div>`,
                        `<div class="small fw-bold text-primary">${detailAcc?.judul || '-'}</div>`,
                        `<div class="small">${item.pembimbing1?.nama_lengkap || 'Belum Set'}</div>`,
                        `<div class="small">${item.pembimbing2?.nama_lengkap || 'Belum Set'}</div>`,
                        `<div class="text-center">${statusBadge}</div>`,
                        `<div class="text-center">${editBtn}</div>`
                    ]);
                });
            }

            // Jika data kosong, draw() akan otomatis menampilkan pesan di language.emptyTable
            table.draw();

            // Update total counter di badge (jika ada)
            const count = table.rows().count();
            $('#totalMhsTerplot').text(`${count} Terplot`);

        } catch (error) {
            console.error('Error:', error);
            table.clear().draw();
        }
    }


    // Ambil data pengajuan tunggal berdasarkan ID
    async getById(id) {
        return await this.ajaxRequest(`${appUrl}/sitasi/ploting/get/${id}`, 'GET');
    }

    // Ambil daftar semua dosen untuk mengisi Select Option
    async getAllDosen() {
        return await this.ajaxRequest(`${appUrl}/sitasi/dosen`, 'GET');
    }

    // Update data pembimbing
    async updatePlotting(id, formData) {
        return await $.ajax({
            url: `${appUrl}/sitasi/ploting/update/${id}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        });
    }

}

export default FinalisasiPlotingService;
