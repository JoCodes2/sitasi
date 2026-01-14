class parameterService {
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

    async getAllData() {
        if ($.fn.dataTable.isDataTable('#parameterTable')) {
            $('#parameterTable').DataTable().clear().destroy();
        }

        $("#parameterTable tbody").empty();

        try {
            const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/parameter-lingkungan/`, 'GET');
            console.log(responseData);

            if (responseData && responseData.data) {
                console.log();

                let tableBody = '';
                responseData.data.forEach((item, index) => {
                    tableBody += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nama_parameter}</td>
                        <td>${item.satuan}</td>
                        <td>${item.kategori}</td>
                        <td>${item.nilai_ideal_min}</td>
                        <td>${item.nilai_ideal_max}</td>
                        <td>${item.deskripsi}</td>
                        <td class="text-center">
                           <div class="d-flex gap-2">
                                <a href="#" class="edit-parameter" data-id="${item.id}" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <a href="#" class="delete-parameter" data-id="${item.id}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    `;
                });

                $("#parameterTable tbody").html(tableBody);

                $('#parameterTable').DataTable({
                    paging: true,
                    searching: true,
                    responsive: true,
                    order: [[0, 'asc']],
                    pageLength: 5,
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
                responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/parameter-lingkungan/update/${id}`, 'POST', formData);
            } else {
                submitButton.attr('disabled', true);
                responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/parameter-lingkungan/create`, 'POST', formData);
            }

            successAlert().then(() => {
                realoadBrowser();
                $('#modalParameter').modal('hide');
            });

        } catch (error) {

            submitButton.attr('disabled', false);

            if (error.status === 422 || error.response?.status === 422) {
                warningAlert();
                return;
            }
            errorAlert();
        }

    }


    async getDataById(id, checkingEdit) {
        try {
            const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/parameter-lingkungan/get/${id}`, 'GET');
            console.log(responseData);

            const data = responseData.data;

            $('#modalParameter').modal('show');

            $('#id').val(data.id);
            $('#nama_parameter').val(data.nama_parameter);
            $('#satuan').val(data.satuan);
            $('#kategori').val(data.kategori);
            $('#nilai_ideal_min').val(data.nilai_ideal_min);
            $('#nilai_ideal_max').val(data.nilai_ideal_max);
            $('#deskripsi').val(data.deskripsi);

            checkingEdit();
        } catch (error) {
            console.log(error);
        }
    }


    async deleteData(id) {
        try {
            const result = await confirmDeleteAlert();
            if (result.isConfirmed) {
                const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/parameter-lingkungan/delete/${id}`, 'DELETE');
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

export default parameterService;
