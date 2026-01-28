class AjukanJudul {

    constructor() {
        this.userId = window.AUTH_USER_ID ?? null;

        if (!this.userId) {
            console.warn('AUTH_USER_ID tidak ditemukan');
        }
    }

    /* ================= AJAX ================= */

    ajaxRequest(url, method = 'GET', data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                type: method,
                data,
                processData: data instanceof FormData ? false : true,
                contentType: data instanceof FormData ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: res => resolve(res),
                error: err => reject(err)
            });
        });
    }

    async checkAlreadySubmitted(gelombangId) {
        if (!this.userId) return null;

        const response = await this.ajaxRequest(
            `${appUrl}/sitasi/pengajuan/submit`
        );

        if (!response?.data || !Array.isArray(response.data)) return null;

        const gelId = String(gelombangId);
        const userId = String(this.userId);

        return response.data.find(p =>
            String(p.gelombang_id) === gelId &&
            String(p.user_id) === userId
        ) || null;
    }
    /* ================= TEMPLATE ================= */

    loadingTemplate() {
        return `
            <div class="text-center py-5">
                <div class="spinner-border text-primary"></div>
                <p class="mt-3 text-muted">Memuat data gelombang...</p>
            </div>
        `;
    }

    noActiveTemplate() {
        return `
        <div class="text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-warning mb-3"></i>
            <h5 class="fw-semibold">Belum Ada Gelombang Aktif</h5>
            <p class="text-muted mb-0">
                Pengajuan judul belum dibuka saat ini.<br>
                Silakan cek kembali secara berkala.
            </p>
        </div>
        `;
    }


    activeTemplate(g) {
        const semester = g?.semester ? String(g.semester).toUpperCase() : '-';

        return `
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-bullhorn me-2"></i>
                        Gelombang Pengajuan Aktif
                    </h5>
                    <small>Silakan ajukan judul penelitian Anda</small>
                </div>
                <span class="badge bg-light text-success px-3 py-2">
                    AKTIF
                </span>
            </div>

            <hr class="border-light">

            <div class="row mb-4">
                <div class="col-md-4">
                    <small class="opacity-75">Gelombang</small>
                    <h6 class="fw-bold">Ke-${g?.gelombang_ke ?? '-'}</h6>
                </div>
                <div class="col-md-4">
                    <small class="opacity-75">Semester</small>
                    <h6 class="fw-bold">${semester}</h6>
                </div>
                <div class="col-md-4">
                    <small class="opacity-75">Tahun Ajaran</small>
                    <h6 class="fw-bold">${g?.tahun_ajaran ?? '-'}</h6>
                </div>
            </div>

            <button class="btn btn-outline-success text-success fw-semibold" id="btnAjukanJudul">
                <i class="fas fa-plus-circle me-1"></i>
                Ajukan Judul
            </button>
        </div>
    </div>
    `;
    }


    alreadySubmittedTemplate(g, p) {
        const semester = g?.semester ? String(g.semester).toUpperCase() : '-';

        return `
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-3x text-info"></i>
                </div>
                <div>
                    <h5 class="mb-1">Pengajuan Sudah Dilakukan</h5>
                    <small class="text-muted">
                        Gelombang ${g?.gelombang_ke ?? '-'} · ${semester} · ${g?.tahun_ajaran ?? '-'}
                    </small>
                </div>
            </div>

            <div class="alert alert-info border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-info-circle me-1"></i>
                        Anda sudah mengajukan di gelombang ini
                    </span>
                </div>
            </div>

            <ul class="list-unstyled text-muted mb-0">
                <li>
                    <i class="fas fa-clock me-2"></i>
                    Silahkan menunggu Informasi tentang pengajuan Anda.
                </li>
            </ul>
        </div>
    </div>
    `;
    }

    errorTemplate() {
        return `
    <div class="text-center py-5">
        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
        <p class="text-muted">
            Terjadi kesalahan saat memuat data.<br>
            Silakan refresh halaman.
        </p>
    </div>
    `;
    }



    async loadActiveGelombang() {
        const container = $('#gelombangContainer');
        container.html(this.loadingTemplate());

        try {
            const gelombangRes = await this.ajaxRequest(
                `${appUrl}/sitasi/gelombang/`
            );

            if (!gelombangRes?.data || !Array.isArray(gelombangRes.data)) {
                container.html(this.errorTemplate());
                return;
            }

            // SIMPAN KE PROPERTI CLASS (this.activeGelombang)
            this.activeGelombang = gelombangRes.data.find(g => Number(g.is_aktif) === 1);

            if (!this.activeGelombang) {
                container.html(this.noActiveTemplate());
                return;
            }

            const alreadySubmitted = await this.checkAlreadySubmitted(this.activeGelombang.id);

            if (alreadySubmitted) {
                container.html(
                    this.alreadySubmittedTemplate(this.activeGelombang, alreadySubmitted)
                );
            } else {
                container.html(
                    this.activeTemplate(this.activeGelombang)
                );
                // Panggil bind button setelah template aktif dirender
                this.bindAjukanButton();
            }

        } catch (e) {
            console.error(e);
            container.html(this.errorTemplate());
        }
    }

    async bindAjukanButton() {
        // Gunakan off() sebelum on() untuk mencegah multiple binding jika fungsi terpanggil dua kali
        $(document).off('click', '#btnAjukanJudul').on('click', '#btnAjukanJudul', async (e) => {
            e.preventDefault();

            if (!this.activeGelombang) {
                return warningAlert("Maaf", "Data gelombang tidak ditemukan.");
            }

            // Tampilkan Form
            $('#formPengajuanContainer').removeClass('d-none');

            // Scroll ke form agar mahasiswa tahu form sudah muncul
            $('html, body').animate({
                scrollTop: $("#formPengajuanContainer").offset().top - 100
            }, 500);

            $('#gelombang_id').val(this.activeGelombang.id);

            loadingAllert('Mohon Tunggu', 'Menyiapkan formulir...');

            try {
                const topik = await this.ajaxRequest(`${appUrl}/sitasi/topik`, 'GET');
                Swal.close();

                this.renderJudulForm(topik.data);

                // Inisialisasi ulang validasi karena form baru dirender
                if (typeof window.initValidation === 'function') {
                    window.initValidation();
                }
            } catch (error) {
                Swal.close();
                errorAlert("Gagal memuat daftar topik.");
            }
        });
    }
    renderJudulForm(topikList) {
        let html = '';
        for (let i = 0; i < 3; i++) {
            html += `
            <div class="card mb-3 border shadow-none">
                <div class="card-header bg-light fw-bold text-primary">OPSI JUDUL ${i + 1}</div>
                <div class="card-body">
                    <input type="hidden" name="details[${i}][pilihan_judul]" value="${i + 1}">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Topik Penelitian</label>
                        <select name="details[${i}][topik_id]" class="form-select" required>
                            <option value="">-- Pilih Topik --</option>
                            ${topikList.map(t => `<option value="${t.id}">${t.nama_topik}</option>`).join('')}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Skripsi</label>
                        <input type="text" name="details[${i}][judul]" class="form-control" placeholder="Tuliskan judul lengkap">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Latar Belakang Singkat</label>
                        <textarea name="details[${i}][latar_belakang]" class="form-control" rows="3" placeholder="Minimal 50 karakter"></textarea>
                    </div>
                </div>
            </div>`;
        }
        $('#judulContainer').html(html);
    }
    async submitPengajuan(formElement) {
        confirmAlert("Apakah Anda yakin data pengajuan judul sudah benar?", async () => {

            const btn = $('#btnSimpan');
            const originalContent = btn.html();

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');

            try {
                const formData = new FormData(formElement);
                const res = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan/create`, 'POST', formData);

                // Berhasil
                successAlert()
                    .then(() => location.reload());

            } catch (error) {
                btn.prop('disabled', false).html(originalContent);

                if (error.status === 422 || error.response?.status === 422) {
                    warningAlert();

                    const errors = error.responseJSON?.data ?? error.response?.data;
                    const validator = $('#formPengajuanJudul').validate();

                    const errorList = {};
                    $.each(errors, function (field, messages) {
                        // Mapping error Laravel ke JQuery Validate
                        let fieldName = field.replace(/\.(\d+)\./g, '[$1][');
                        if (fieldName.includes('[')) fieldName += ']';
                        errorList[fieldName] = messages[0];
                    });
                    validator.showErrors(errorList);
                } else {
                    console.error("Detail Error:", error);
                    errorAlert();
                }
            }
        });
    }
}

export default AjukanJudul;
