import judulService from "../services/judul.service.js";

$(document).ready(function () {
    const service = new judulService();
    let currentId = null;

    const loadData = async () => {
        const data = await service.getAllData();
        const $tbody = $("#judulBody");

        if (data.length === 0) {
            service.renderEmptyState();
            return;
        }

        let html = "";
        data.forEach((item, index) => {
            const statusBadge = {
                'pending': '<span class="badge bg-label-warning">Menunggu</span>',
                'approved': '<span class="badge bg-label-success">Disetujui</span>',
                'rejected': '<span class="badge bg-label-danger">Ditolak</span>',
                'published': '<span class="badge bg-label-primary">Dipublikasi</span>'
            };

            // Format Tanggal (Indo)
            const tgl = new Date(item.created_at).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Extract NIM dan Angkatan
            const nim = service.extractNimFromEmail(item.user.email);

            // Format Gelombang
            const semester = service.formatSemester(item.gelombang.semester);
            const gelombangInfo = `Gelombang ${item.gelombang.gelombang_ke} - ${semester} ${item.gelombang.tahun_ajaran}`;

            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <small>${tgl}</small>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">${item.user.nama}</span>
                            <small class="text-muted">${item.user.mahasiswa.nim}</small>
                            <small class="text-muted">${item.user.mahasiswa.angkatan}</small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span>${gelombangInfo}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold text-primary">Pilihan ${item.harapan_judul}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-info btn-detail"
                                    data-id="${item.id}"
                                    title="Lihat Detail">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $tbody.html(html);
    };

    $(document).on('click', '.btn-detail', async function () {
        currentId = $(this).data('id');
        $('#modalDetailPengajuan').modal('show');
        $('#loaderModal').removeClass('d-none');
        $('#contentModal').addClass('d-none');

        try {
            const response = await service.getDataById(currentId);
            const item = response.data;


            // Fill Informasi Mahasiswa
            $('#detNama').text(item.user.nama);
            $('#detNim').text(item.user.mahasiswa.nim);
            $('#detAngkatan').text(item.user.mahasiswa.angkatan);

            // Fill Informasi Gelombang
            $('#detGelombangKe').text(`Gelombang ${item.gelombang.gelombang_ke}`);
            $('#detSemester').text(service.formatSemester(item.gelombang.semester));
            $('#detTahunAjaran').text(item.gelombang.tahun_ajaran);

            // Fill Judul yang Diharapkan
            const harapanBadge = {
                '1': '<button class="btn btn-outline-primary">Pilihan 1</button>',
                '2': '<button class="btn btn-outline-success">Pilihan 2</button>',
                '3': '<button class="btn btn-outline-info">Pilihan 3</button>'
            };
            $('#detHarapanJudul').html(harapanBadge[item.harapan_judul] || item.harapan_judul);
            $('#detAlasan').html(item.alasan_prioritas.replace(/\n/g, '<br>'));

            // Fill Detail 3 Judul
            // Reset status badges
            for (let i = 1; i <= 3; i++) {
                $(`#status-${i}`).removeClass('bg-label-success bg-label-danger')
                    .addClass('bg-label-secondary')
                    .text('Belum diproses');
            }

            // Jika ada judul yang sudah di-approve
            if (item.indeks_judul_acc) {
                $(`#status-${item.indeks_judul_acc}`)
                    .removeClass('bg-label-secondary')
                    .addClass('bg-label-success')
                    .text('Disetujui');
            }

            item.detail_pengajuan.forEach((detail) => {
                const idx = detail.pilihan_judul;
                $(`#topik-${idx}`).text(detail.topik.nama_topik);
                $(`#title-${idx}`).text(detail.judul);
                $(`#lb-${idx}`).html(detail.latar_belakang.replace(/\n/g, '<br>'));
            });

            // Update button plotting
            $('.btn-plotting').data('id', item.id);

            $('#loaderModal').addClass('d-none');
            $('#contentModal').removeClass('d-none');
        } catch (error) {
            console.error('Error loading detail:', error);
            $('#modalDetailPengajuan').modal('hide');
            errorAlert("Gagal mengambil detail data.");
        }
    });

    // Initial load
    loadData();
});
