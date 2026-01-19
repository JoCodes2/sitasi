import judulService from "../services/judul.service.js";

$(document).ready(function () {
    const service = new judulService();

    let currentId = null;
    let detailMap = {};

    const baseBody = document.querySelector('[data-is-admin]');
    const isAdmin = baseBody?.dataset.isAdmin === '1';


    // =============================
    // LOAD TABLE DATA
    // =============================
    const loadData = async () => {
        const data = await service.getAllData();
        const $tbody = $("#judulBody");

        if (data.length === 0) {
            service.renderEmptyState();
            return;
        }

        let html = "";
        data.forEach((item, index) => {
            const tgl = new Date(item.created_at).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            const semester = service.formatSemester(item.gelombang.semester);
            const gelombangInfo = `Gelombang ${item.gelombang.gelombang_ke} - ${semester} ${item.gelombang.tahun_ajaran}`;

            html += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td><small>${tgl}</small></td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">${item.user.nama}</span>
                            <small class="text-muted">${item.user.mahasiswa.nim}</small>
                            <small class="text-muted">${item.user.mahasiswa.angkatan}</small>
                        </div>
                    </td>
                    <td>${gelombangInfo}</td>
                    <td class="text-center">
                        <span class="fw-bold text-primary">Pilihan ${item.harapan_judul}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info btn-detail"
                                data-id="${item.id}">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        $tbody.html(html);
    };

    // =============================
    // DETAIL MODAL
    // =============================
    $(document).on('click', '.btn-detail', async function () {
        currentId = $(this).data('id');
        detailMap = {};

        $('#modalDetailPengajuan').modal('show');
        $('#loaderModal').removeClass('d-none');
        $('#contentModal').addClass('d-none');

        try {
            const response = await service.getDataById(currentId);
            const item = response.data;

            // ===== MAHASISWA =====
            $('#detNama').text(item.user.nama);
            $('#detNim').text(item.user.mahasiswa.nim);
            $('#detAngkatan').text(item.user.mahasiswa.angkatan);

            // ===== GELOMBANG =====
            $('#detGelombangKe').text(`Gelombang ${item.gelombang.gelombang_ke}`);
            $('#detSemester').text(service.formatSemester(item.gelombang.semester));
            $('#detTahunAjaran').text(item.gelombang.tahun_ajaran);

            // ===== PRIORITAS =====
            $('#detHarapanJudul').html(`<span class="badge bg-primary">Pilihan ${item.harapan_judul}</span>`);
            $('#detAlasan').html(item.alasan_prioritas.replace(/\n/g, '<br>'));

            // ===== RESET UI =====
            for (let i = 1; i <= 3; i++) {
                $(`#title-${i}`).html('-');
                $(`#topik-${i}`).text('-');
                $(`#lb-${i}`).text('-');
            }

            // ===== DETAIL JUDUL =====
            item.detail_pengajuan.forEach(detail => {
                const idx = detail.pilihan_judul;
                detailMap[idx] = detail.id;

                const topikNama = detail.topik.nama_topik || '-';

                let badgeClass = 'bg-secondary';
                let badgeText = 'Belum Diproses';

                if (detail.status_judul === 'approved') {
                    badgeClass = 'bg-success';
                    badgeText = 'Disetujui';
                } else if (detail.status_judul === 'rejected') {
                    badgeClass = 'bg-danger';
                    badgeText = 'Ditolak';
                } else if (detail.status_judul === 'confirmation') {
                    badgeClass = 'bg-info';
                    badgeText = 'Butuh Konfirmasi';
                }

                const isLocked = ['approved', 'rejected'].includes(detail.status_judul);

                const actionHtml = (isAdmin && !item.indeks_judul_acc && !isLocked) ? `
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-success btn-acc" data-index="${idx}">ACC</button>
                        <button class="btn btn-sm btn-danger btn-reject" data-index="${idx}">Tolak</button>
                        <button class="btn btn-sm btn-info btn-confirm" data-index="${idx}">Konfirmasi</button>
                    </div>
                ` : '';


                const headerHtml = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge ${badgeClass}">${badgeText}</span>
                        ${actionHtml}
                    </div>
                `;

                $(`#title-${idx}`).html(headerHtml + `<div class="fw-bold">${detail.judul}</div>`);
                $(`#topik-${idx}`).text(topikNama);
                $(`#lb-${idx}`).text(detail.latar_belakang);
            });

            // ===== LOCK JIKA SUDAH ACC =====
            if (item.indeks_judul_acc) {
                $('.btn-acc, .btn-reject, .btn-confirm').remove();
            }

            $('#loaderModal').addClass('d-none');
            $('#contentModal').removeClass('d-none');

        } catch (error) {
            console.error(error);
            $('#modalDetailPengajuan').modal('hide');
        }
    });

    // =============================
    // ACTION BUTTON
    // =============================
    $(document).on('click', '.btn-acc, .btn-reject, .btn-confirm', function () {
        if (!isAdmin) return;

        const index = $(this).data('index');
        const detailId = detailMap[index];
        if (!detailId) return;

        let status = 'confirmation';
        let confirmMessage = 'Yakin ingin memproses judul ini?';

        if ($(this).hasClass('btn-acc')) {
            status = 'approved';
            confirmMessage = 'Yakin ingin MENYETUJUI judul ini?';
        }

        if ($(this).hasClass('btn-reject')) {
            status = 'rejected';
            confirmMessage = 'Yakin ingin MENOLAK judul ini?';
        }

        if ($(this).hasClass('btn-confirm')) {
            status = 'confirmation';
            confirmMessage = 'Yakin ingin meminta KONFIRMASI judul ini?';
        }

        confirmAlert(confirmMessage, async () => {
            try {
                await service.updateStatusJudul(detailId, status);

                successAlert();
                $('#modalDetailPengajuan').modal('hide');
                realoadBrowser();

            } catch (error) {
                console.error(error);
                errorAlert('Gagal mengubah status');
            }
        });
    });



    // =============================
    // INIT
    // =============================
    loadData();
});
