import PlottingService from "../services/ploting-dosen.service.js";

$(document).ready(function () {
    const plotingDosen = new PlottingService();
    plotingDosen.getJudulApproved();
    plotingDosen.getAllDosen();

    // 1. Load data saat awal
    plotingDosen.renderHasilPlotting();

    $('#btnProsesPlotting').on('click', function () {
        confirmAlert1(
            "Konfirmasi Plotting",
            "Sistem akan menjalankan algoritma pembagian otomatis. Lanjutkan?",
            async () => {
                loadingAllert("Memproses...", "Sistem sedang menghitung pembagian terbaik.");

                try {
                    const response = await plotingDosen.triggerProsesPlotting();

                    if (response.code === 200) {
                        Swal.close();

                        successAlert("Berhasil!", "Pembagian dosen telah selesai disimpan.")
                            .then(() => {
                                $('#hasilPlottingContainer').removeClass('d-none');
                                $('#totalMhsTerplot').text(`${response.data.sukses} Terplot`);
                                plotingDosen.renderHasilPlotting();
                            });
                    }
                    // TAMBAHKAN BLOK INI UNTUK HANDLE CODE 400
                    else if (response.code === 400) {
                        Swal.close(); // Tutup loading alert terlebih dahulu
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.message || "Tidak ada data yang memenuhi syarat untuk di-plotting.",
                            confirmButtonColor: '#48ABF7'
                        });
                    }
                    else {
                        errorAlert("Gagal", response.message || "Terjadi kesalahan.");
                    }
                } catch (error) {
                    errorAlert("Proses Gagal", "Terjadi kesalahan saat menghubungi server.");
                }
            }
        );
    });
    // Tambahkan di dalam $(document).ready() pada controller Anda

    // Event Klik Tombol Finalisasi
    $('#btnFinalisasiPlotting').on('click', function () {
        confirmAlert1(
            "Finalisasi Plotting?",
            "Data akan disimpan secara permanen, status judul menjadi 'finalisasi', dan pilihan judul lainnya akan dihapus.",
            async () => {
                loadingAllert("Memproses Finalisasi...");
                try {
                    const response = await plotingDosen.triggerFinalisasi();
                    console.log(response);

                    if (response.code === 200) {
                        successAlert("Berhasil!", response.message).then(() => {
                            $('#hasilPlottingContainer').addClass('d-none');

                            $('#hasilPlottingBody').empty();
                            realoadBrowser();
                        });
                    } else {
                        errorAlert("Gagal", response.message);
                    }
                } catch (error) {
                    console.error(error);
                    errorAlert("Gagal", "Terjadi kesalahan saat memfinalisasi data.");
                }
            }
        );
    });
});
