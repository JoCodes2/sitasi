class gelombangService {
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
    async getActiveGelombang() {
        try {
            const response = await $.ajax({
                url: `${appUrl}/sitasi/gelombang/`,
                method: 'GET'
            });

            // Cari data yang is_aktif-nya bernilai 1
            const activeData = response.data.find(item => item.is_aktif == 1);

            return {
                success: !!activeData,
                data: activeData
            };
        } catch (error) {
            console.error('Error fetching gelombang:', error);
            return { success: false, data: null };
        }
    }

    async getAllData() {
        let table = $('#gelombangTable').DataTable();
        try {
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/gelombang/`, 'GET');

            table.clear();

            if (responseData.code === 200 && responseData.data && responseData.data.length > 0) {
                responseData.data.forEach((item, index) => {

                    const statusBtn = item.is_aktif == 1
                        ? `<button class="btn btn-sm btn-success fw-bold px-3 shadow-sm" style="border-radius: 20px; min-width: 100px; pointer-events: none;">
                        <i class="fas fa-check-circle me-1"></i> AKTIF
                       </button>`
                        : `<button class="btn btn-sm btn-secondary fw-bold px-3 shadow-sm" style="border-radius: 20px; min-width: 100px; pointer-events: none;">
                        <i class="fas fa-times-circle me-1"></i> TUTUP
                       </button>`;
                    const semesterBtn = item.semester.toLowerCase() === 'ganjil'
                        ? `<button class="btn btn-xs btn-outline-primary fw-bold text-uppercase" style="width: 80px; pointer-events: none;">Ganjil</button>`
                        : `<button class="btn btn-xs btn-outline-warning fw-bold text-uppercase" style="width: 80px; pointer-events: none;">Genap</button>`;

                    const actions = `
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-info btn-sm btnEdit" data-id="${item.id}" title="Edit">
                            <i class="fa fa-edit text-white"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btnHapus" data-id="${item.id}" title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>`;

                    table.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        `<span class="fw-bold text-dark">${item.tahun_ajaran}</span>`,
                        `<div class="text-center">${semesterBtn}</div>`,
                        `<div class="text-center fw-bold">Gelombang ${item.gelombang_ke}</div>`,
                        `<span class="text-muted"><i class="far fa-calendar-alt me-1"></i> ${item.tgl_mulai}</span>`,
                        `<span class="text-muted"><i class="far fa-calendar-check me-1"></i> ${item.tgl_selesai}</span>`,
                        `<div class="text-center">${statusBtn}</div>`, // Kolom Status
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
        const $table = $('#gelombangTable');
        const $tbody = $("#gelombangBody");

        if ($.fn.dataTable.isDataTable('#gelombangTable')) {
            $table.DataTable().destroy();
        }

        $tbody.html(`
        <tr>
            <td colspan="8" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data gelombang. Klik <strong>Tambah Gelombang</strong> untuk memulai.</p>
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
            const responseData = await this.ajaxRequest(`${appUrl}/sitasi/gelombang/get/${id}`, 'GET');

            const item = responseData.data;

            $('#modalTambahGelombang').modal('show');

            $('#modalTambahGelombang .modal-title').text('Edit Gelombang Pengajuan');

            $('#id').val(item.id);
            $('#tahun_ajaran').val(item.tahun_ajaran);
            $('#semester').val(item.semester);
            $('#gelombang_ke').val(item.gelombang_ke);
            $('#tgl_mulai').val(item.tgl_mulai);
            $('#tgl_selesai').val(item.tgl_selesai);

            if (item.is_aktif == 1) {
                $('#is_aktif').prop('checked', true);
            } else {
                $('#is_aktif').prop('checked', false);
            }

            $('#formGelombang').validate().resetForm();
            $('#formGelombang .form-control').removeClass('is-invalid');

        } catch (error) {
            console.error('Error saat mengambil data:', error);
            errorAlert("Gagal mengambil data detail gelombang.");
        }
    }

    async deleteData(id) {
        try {
            const result = await confirmDeleteAlert();
            if (result.isConfirmed) {
                const responseData = await this.ajaxRequest(`${appUrl}/sitasi/gelombang/delete/${id}`, 'DELETE');
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

export default gelombangService;
