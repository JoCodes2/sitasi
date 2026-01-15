class loginService {
    async ajaxRequest(url, method, formData) {
        try {
            const response = await $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false
            });
            return response;
        } catch (jqXHR) {
            throw {
                status: jqXHR.status,
                responseJSON: jqXHR.responseJSON || {}
            };
        }
    }

    async login(e) {
        try {
            Swal.fire({
                title: 'Loading...',
                html: 'Please wait while processing...',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(e.target);
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/login`, 'POST', formData);

            if (responseData.status === 'success') {
                Swal.close();
                successAlert().then(() => {
                    window.location.href = `${appUrl}/`;
                });
            }
        } catch (error) {
            Swal.close();
            console.error('Error:', error);
            if (error.status === 401) {
                warningAlert("Login gagal.");
            } else if (error.status === 422) {
                warningAlert("Mohon periksa kembali inputan Anda.");
            } else {
                errorAlert("Terjadi kesalahan sistem.");
            }
        }
    }
}

export default loginService;
