import riwayatService from "../services/riwayat.service.js";

$(document).ready(function () {
    const riwayat = new riwayatService();
    riwayat.getAllData();
});
