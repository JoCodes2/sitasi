import FinalisasiPlotingService from "../services/finalisasi-ploting.service.js";


$(document).ready(function () {
    const service = new FinalisasiPlotingService();


    service.getAllData();

    // 2. Fungsi Load Dosen ke Select Option (Dijalankan sekali saat halaman siap)
    async function loadDosenOptions() {
        try {
            const res = await service.getAllDosen();
            if (res.code === 200) {
                let options = '<option value="">-- Pilih Dosen --</option>';
                res.data.forEach(dosen => {
                    options += `<option value="${dosen.id}">${dosen.nama_lengkap} ${dosen.gelar || ''}</option>`;
                });
                $('#dosen_pembimbing_1_id, #dosen_pembimbing_2_id').html(options);
            }
        } catch (error) {
            console.error("Gagal load data dosen", error);
        }
    }
    loadDosenOptions();

    $(document).on('click', '.btnEdit', async function () {
        const id = $(this).data('id');
        try {
            const res = await service.getById(id);
            if (res.code === 200) {
                Swal.close();
                const data = res.data;

                $('#id_pengajuan').val(data.id);
                $('#display_nama_mhs').text(data.user.nama);

                $('#dosen_pembimbing_1_id').val(data.dosen_pembimbing_1_id).trigger('change');
                $('#dosen_pembimbing_2_id').val(data.dosen_pembimbing_2_id).trigger('change');

                $('#modalEditPlotting').modal('show');
            }
        } catch (error) {
            errorAlert("Gagal", "Data tidak ditemukan");
        }
    });

    $('#btnSimpanPlotting').on('click', async function () {
        const id = $('#id_pengajuan').val();
        const formData = new FormData($('#formEditPlotting')[0]);

        confirmAlert1("Update Pembimbing?", "Status akan otomatis berubah menjadi Published.", async () => {
            loadingAllert("Menyimpan data...");
            try {
                const res = await service.updatePlotting(id, formData);
                if (res.code === 200) {
                    successAlert("Berhasil!", res.message).then(() => {
                        $('#modalEditPlotting').modal('hide');
                        realoadBrowser();
                    });
                }
            } catch (error) {
                errorAlert("Gagal", "Terjadi kesalahan saat menyimpan data.");
            }
        });
    });
});
