class mahasiswaService {
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
        let table = $('#mahasiswaTable').DataTable();
        try {
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/mahasiswa/`, 'GET');

            table.clear();

            if (responseData.code === 200 && responseData.data && responseData.data.length > 0) {
                responseData.data.forEach((item, index) => {

                    const genderIcon = item.jenis_kelamin === 'L'
                        ? '<i class="fa-solid fa-mars text-primary ml-1" title="Laki-laki"></i>'
                        : '<i class="fa-solid fa-venus text-danger ml-1" title="Perempuan"></i>';

                    const infoMhs = `
                    <div class="d-flex align-items-center">
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle mr-2" style="width:35px; height:35px;">
                            <i class="fa-solid fa-user text-secondary"></i>
                        </div>
                        <div>
                            <span class="font-weight-bold d-block text-dark">${item.user.nama ?? 'Mahasiswa ' + (index + 1)} ${genderIcon}</span>
                            <small class="text-muted">${item.no_hp}</small>
                        </div>
                    </div>`;

                    const prodiBadge = item.prodi === 'TI'
                        ? '<button class="btn btn-xs btn-outline-primary fw-bold p-1" >Teknik Informatika</button>'
                        : '<button class="btn btn-xs btn-outline-success fw-bold p-1" >Sistem Informasi</button>';

                    const actions = `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-info btn-sm detailMahasiswa" data-id="${item.id}" title="Detail">
                            <i class="fa fa-eye text-white"></i>
                        </button>
                    </div>`;

                    table.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        infoMhs,
                        `<code class="text-primary font-weight-bold" style="font-size: 14px;">${item.nim}</code>`,
                        `<div class="text-center">${prodiBadge}</div>`,
                        `<div class="text-center font-weight-bold text-secondary">${item.angkatan}</div>`,
                        actions
                    ]);
                });

                table.draw();

            } else {
                this.renderEmptyState();
            }

        } catch (error) {
            console.error('Error saat mengambil data:', error);
            this.renderEmptyState();
        }
    }
    renderEmptyState() {
        const $table = $('#mahasiswaTable');
        const $tbody = $("#mahasiswaBody");

        if ($.fn.dataTable.isDataTable('#mahasiswaTable')) {
            $table.DataTable().destroy();
        }

        $tbody.html(`
        <tr>
            <td colspan="8" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data mahasiswa</p>
                </div>
            </td>
        </tr>
    `);
    }
    async upsertData(formElement, checkingEdit) {
        const submitButton = $('#btnSimpanGelombang');
        const originalText = submitButton.html();

        try {
            const formData = new FormData(formElement);

            if (!$('#is_aktif').is(':checked')) {
                formData.set('is_aktif', 0);
            }

            submitButton.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Loading...');

            let responseData;
            if (checkingEdit()) {
                const id = $('#id').val();
                responseData = await this.ajaxRequest(`${appUrl}/sitasi/gelombang/update/${id}`, 'POST', formData);
            } else {
                responseData = await this.ajaxRequest(`${appUrl}/sitasi/gelombang/create`, 'POST', formData);
            }

            successAlert().then(() => {
                $('#modalTambahGelombang').modal('hide');
                realoadBrowser();
                submitButton.attr('disabled', false).html(originalText);
            });

        } catch (error) {
            submitButton.attr('disabled', false).html(originalText);

            if (error.status === 422 || error.response?.status === 422) {
                warningAlert();
                const errors = error.responseJSON?.data ?? error.response?.data;
                const validator = $('#formGelombang').validate();

                const errorList = {};
                $.each(errors, function (field, messages) {
                    errorList[field] = messages[0];
                });
                validator.showErrors(errorList);
                return;
            }

            console.error("Detail Error:", error);
            errorAlert();
        }
    }



    async getDataById(id) {
        try {
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/mahasiswa/get/${id}`, 'GET');
            const item = responseData.data;


            const formatTitleCase = (str) => {
                if (!str) return '-';
                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            };

            const firstTab = new bootstrap.Tab($('#pills-biodata-tab'));
            firstTab.show();

            $('#det_nama').text(item.user.nama);
            $('#det_nim').text(item.nim);
            $('#det_ttl').text(`${item.tempat_lahir}, ${item.tanggal_lahir}`);

            $('#det_agama').text(formatTitleCase(item.agama));

            $('#det_jk').text(item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
            $('#det_prodi').text(item.prodi === 'TI' ? 'Teknik Informatika' : 'Sistem Informasi');
            $('#det_angkatan').text(item.angkatan);
            $('#det_alamat').text(item.alamat);

            $('#det_email').text(item.user.email);
            $('#det_no_hp').text(item.no_hp);

            $('#modalDetailMahasiswa').modal('show');

        } catch (error) {
            console.error('Error detail:', error);
        }
    }

}

export default mahasiswaService;
