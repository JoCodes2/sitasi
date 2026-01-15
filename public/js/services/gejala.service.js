class gejalaService {
    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                processData: false,
                contentType: false,
                success: (response) => resolve(response),
                error: (error) => reject(error),
            });
        });
    }

    getKategoriBadge(kategori) {
        const colors = {
            'akar': 'badge bg-danger',
            'daun': 'badge bg-success',
            'buah': 'badge bg-warning',
            'batang': 'badge bg-primary',
            'umum': 'badge bg-secondary'
        };

        return `<span class="${colors[kategori.toLowerCase()] || 'badge bg-dark'}">
                ${kategori}
            </span>`;
    }
    async getAllData() {
        if ($.fn.dataTable.isDataTable('#gejalaTable')) {
            $('#gejalaTable').DataTable().clear().destroy();
        }

        $("#gejalaTable tbody").empty();

        try {
            const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/gejala/`, 'GET');
            console.log(responseData);

            if (responseData && responseData.data) {

                const sortedData = responseData.data.sort((a, b) => {
                    const numA = parseInt(a.kode_gejala.replace(/\D/g, ''));
                    const numB = parseInt(b.kode_gejala.replace(/\D/g, ''));
                    return numA - numB;
                });

                let tableBody = '';
                sortedData.forEach((item, index) => {
                    tableBody += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.kode_gejala}</td>
                    <td>${this.getKategoriBadge(item.kategori)}</td>
                    <td>${item.deskripsi_gejala}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2">
                            <a href="#" class="edit-gejala" data-id="${item.id}" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <a href="#" class="delete-gejala" data-id="${item.id}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                `;
                });

                $("#gejalaTable tbody").html(tableBody);

                $('#gejalaTable').DataTable({
                    paging: true,
                    searching: true,
                    responsive: true,
                    order: [[0, 'asc']],
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                });

            } else {
                console.error('Response data is invalid:', responseData);
            }
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }


    async upsertData(e, checkingEdit) {
        let submitButton = $(e.target).find(':submit');

        try {
            const formData = new FormData(e.target);
            let responseData;
            if (checkingEdit()) {
                const id = $('#id').val();
                responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/gejala/update/${id}`, 'POST', formData);
            } else {
                submitButton.attr('disabled', true);
                responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/gejala/create`, 'POST', formData);
            }

            successAlert().then(() => {
                realoadBrowser();
                $('#modalGejala').modal('hide');
            });

        } catch (error) {

            submitButton.attr('disabled', false);

            if (error.status === 422 || error.response?.status === 422) {
                warningAlert();
                let errors = error.responseJSON?.data ?? error.response?.data;

                let validator = $('#formGejala').validate();

                validator.resetForm();

                $.each(errors, function (field, messages) {
                    validator.showErrors({
                        [field]: messages[0]
                    });
                });

                return;
            }
            errorAlert();
        }

    }



    async getDataById(id, checkingEdit) {
        try {
            const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/gejala/get/${id}`, 'GET');
            console.log(responseData);
            $('#modalGejala').modal('show');
            $('#id').val(responseData.data.id);
            $('#kode_gejala').val(responseData.data.kode_gejala);
            $('#deskripsi_gejala').val(responseData.data.deskripsi_gejala);
            $('#kategori').val(responseData.data.kategori);
            checkingEdit();
        } catch (error) {
            console.log(error);
        }
    }

    async deleteData(id) {
        try {
            const result = await confirmDeleteAlert();
            if (result.isConfirmed) {
                const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/gejala/delete/${id}`, 'DELETE');
                console.log(responseData);
                if (responseData.code === 200) {
                    await successAlert().then(() => {
                        realoadBrowser();
                    });
                } else {
                    errorAlert();
                }
            }
        } catch (error) {
            errorAlert();
        }
    }

}

export default gejalaService;
