import PlottingService from "../services/ploting-dosen.service.js";

$(document).ready(function () {
    const plotingDosen = new PlottingService();
    plotingDosen.getJudulApproved();
    plotingDosen.getAllDosen();
});
