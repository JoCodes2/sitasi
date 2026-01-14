// reload browser
function reloadBrowser() {
    window.location.reload();
}

// alias for typo
function realoadBrowser() {
    reloadBrowser();
}

// loading alert
function loadingAlert() {
    return Swal.fire({
        title: 'Memuat...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

// alias for typo
function loadingAllert() {
    loadingAlert();
}

// confirm alert
function confirmAlert(message, callback) {
    Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// reload browsers (alias)
function reloadBrowsers() {
    reloadBrowser();
}

// alert confirm message
function confirmDeleteAlert(message) {
    return Swal.fire({
        title: '<span style="font-size: 22px"> Konfirmasi</span>',
        text: "Apakah anda yakin?",
        showCancelButton: true,
        showConfirmButton: true,
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya',
        reverseButtons: true,
        confirmButtonColor: '#48ABF7',
        cancelButtonColor: '#EFEFEF',
        customClass: {
            cancelButton: 'text-dark'
        }
    });
}



// alert success message
function successAlert(message) {
    return Swal.fire({
        title: 'Berhasil!',
        text: message,
        icon: 'success',
        showConfirmButton: false,
        timer: 1000,
    });
}

function errorAlert() {
    return Swal.fire({
        title: 'Error',
        text: 'Terjadi kesalahan!',
        icon: 'error',
        showConfirmButton: false,
        timer: 1000,
    });
}
function warningAlert(message) {
    Swal.fire({
        title: 'Peringatan !',
        text: message,
        icon: 'warning',
        timer: 5000,
        showConfirmButton: true,
        confirmButtonText: 'Ok',
        confirmButtonColor: '#FFAD46',
    });
}

function emailOrPasswordWrong() {
    return Swal.fire({
        title: 'Peringatan',
        text: 'username atau password anda salah !',
        icon: 'warning',
        timer: 5000,
        showConfirmButton: true
    });
}



function exportAlert(message) {
    return Swal.fire({
        title: '<span style="font-size: 22px"> Konfirmasi</span>',
        text: "Apakah anda yakin?",
        showCancelButton: true,
        showConfirmButton: true,
        cancelButtonText: 'Tidak',
        confirmButtonText: 'Ya',
        reverseButtons: true,
        confirmButtonColor: '#48ABF7',
        cancelButtonColor: '#EFEFEF',
        customClass: {
            cancelButton: 'text-dark'
        }
    });
}
