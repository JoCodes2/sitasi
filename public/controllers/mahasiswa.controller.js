import mahasiswaService from "../services/mahasiswa.service.js"

$(document).ready(function () {
    const mahasiswa = new mahasiswaService();
    mahasiswa.getAllData();

    // Di dalam $(document).ready(...)
    $(document).on('click', '.detailMahasiswa', function () {
        const id = $(this).data('id');
        mahasiswa.getDataById(id);
    });
})
