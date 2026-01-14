/**
 * Diagnosa Controller - Handle DOM manipulation dengan jQuery Validate
 */
class DiagnosaController {
    constructor(service) {
        this.service = service;
        this.gejalaData = [];
        this.parameterData = [];
        this.selectedGejala = [];
        this.validator = null;
    }

    /**
     * Initialize controller
     */
    async init() {
        await this.loadData();
        this.bindEvents();
        this.setupValidation();
    }

    /**
     * Load initial data
     */
    async loadData() {
        // Load parameter lingkungan
        const parameterResult = await this.service.getParameterLingkungan();
        if (!parameterResult.success) return;
        this.parameterData = parameterResult.data;

        // Load gejala
        const gejalaResult = await this.service.getGejala();
        if (!gejalaResult.success) return;
        this.gejalaData = gejalaResult.data;

        this.renderParameterForm();
        this.renderGejalaList();
    }

    /**
     * Setup jQuery Validate dengan showErrors untuk full control
     */
    /**
     * Setup jQuery Validate dengan custom handling untuk semua checkbox
     */
    setupValidation() {
        // Dapatkan rules dari service
        const { rules, messages } = this.service.getJqueryValidateRules(this.parameterData);

        // Setup jQuery Validate
        this.validator = $('#diagnosaForm').validate({
            rules: {
                ...rules,
                'gejala[]': {
                    required: true
                }
            },
            messages: {
                ...messages,
                'gejala[]': {
                    required: "Pilih minimal 1 gejala tanaman"
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',

            // Custom highlight untuk SEMUA checkbox
            highlight: function (element, errorClass, validClass) {
                // Untuk checkbox gejala[]
                if (element.name === 'gejala[]') {
                    // Tambah class error pada SEMUA checkbox
                    $('input[name="gejala[]"]').addClass('error-border');
                    $('input[name="gejala[]"] + .form-check-label').addClass('error-label');

                    // Highlight container
                    $('#gejalaList').addClass('error-container');
                } else {
                    // Untuk input biasa
                    $(element).addClass('is-invalid');
                }
            },

            // Custom unhighlight untuk SEMUA checkbox
            unhighlight: function (element, errorClass, validClass) {
                if (element.name === 'gejala[]') {
                    // Hapus class error hanya jika checkbox ini sudah di-check
                    if ($(element).is(':checked')) {
                        $(element).removeClass('error-border');
                        $(element).next('.form-check-label').removeClass('error-label');
                    }

                    // Cek apakah semua checkbox sudah valid
                    const checkedCount = $('input[name="gejala[]"]:checked').length;
                    if (checkedCount > 0) {
                        // Hapus semua error styling
                        $('input[name="gejala[]"]').removeClass('error-border');
                        $('input[name="gejala[]"] + .form-check-label').removeClass('error-label');
                        $('#gejalaList').removeClass('error-container');
                    }
                } else {
                    $(element).removeClass('is-invalid');
                }
            },

            // Custom error placement
            errorPlacement: function (error, element) {
                if (element.attr("name") === "gejala[]") {
                    // Untuk gejala, tempatkan error di container khusus
                    $('#gejalaErrorContainer').html(error);
                } else {
                    // Untuk input biasa
                    error.insertAfter(element);
                }
            },

            // Handler saat form invalid
            invalidHandler: (event, validator) => {
                // Jika gejala[] error, highlight semua checkbox
                if (validator.errorMap['gejala[]']) {
                    $('input[name="gejala[]"]').addClass('error-border');
                    $('input[name="gejala[]"] + .form-check-label').addClass('error-label');
                    $('#gejalaList').addClass('error-container');
                }
            },

            submitHandler: (form) => {
                this.handleSubmit(form);
                return false;
            }
        });
    }

    /**
     * Bind all event listeners
     */
    /**
     * Bind all event listeners
     */
    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Checkbox events dengan debounce untuk performance
        $(document).on('change', 'input[name="gejala[]"]', (e) => {
            this.handleGejalaCheck(e);

            // Validasi real-time dengan delay untuk UX yang lebih baik
            setTimeout(() => {
                const isValid = this.validator.element('[name="gejala[]"]');

                if (isValid) {
                    // Hapus semua error styling jika valid
                    this.removeGejalaErrorStyling();
                } else {
                    // Tambah error styling jika invalid
                    this.applyGejalaErrorStyling();
                }
            }, 300);
        });

        // Button handlers
        $(document).on('click', '#diagnosaLagiBtn', () => this.resetForm());
    }

    /**
     * Apply error styling pada semua checkbox gejala
     */
    applyGejalaErrorStyling() {
        $('input[name="gejala[]"]').addClass('error-border');
        $('input[name="gejala[]"] + .form-check-label').addClass('error-label');
        $('#gejalaList').addClass('error-container');
    }

    /**
     * Remove error styling dari semua checkbox gejala
     */
    removeGejalaErrorStyling() {
        $('input[name="gejala[]"]').removeClass('error-border');
        $('input[name="gejala[]"] + .form-check-label').removeClass('error-label');
        $('#gejalaList').removeClass('error-container');
    }

    /**
     * Render parameter form
     */
    renderParameterForm() {
        let html = '<div class="row">';

        this.parameterData.forEach(param => {
            const fieldName = param.nama_parameter.toLowerCase().replace(/ /g, '_');
            const min = param.nilai_ideal_min;
            const max = param.nilai_ideal_max;

            html += `
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="${fieldName}" class="form-label">
                        ${param.nama_parameter}
                        <span class="text-muted">(${param.satuan})</span>
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="${fieldName}"
                        name="kondisi_lingkungan[${fieldName}]"
                        step="0.1"
                        placeholder="Masukkan nilai ${param.nama_parameter.toLowerCase()}"
                        data-parameter="${fieldName}"
                        data-min="${min || ''}"
                        data-max="${max || ''}"
                    >
                    <div class="form-text">
                        ${min !== null && max !== null
                    ? `Ideal: ${min} - ${max} ${param.satuan}`
                    : 'Tidak ada range ideal'}
                    </div>
                </div>
            </div>
            `;
        });

        html += '</div>';
        $('#kondisiLingkunganForm').html(html);
    }

    /**
     * Render gejala list grouped by kategori
     */
    /**
     * Render gejala list grouped by kategori
     */
    /**
  * Render gejala list grouped by kategori - 2 kolom layout
  */
    renderGejalaList() {
        let html = `
    <div id="gejalaErrorContainer" class="mb-3"></div>
    `;

        // Group gejala by kategori
        const gejalaByKategori = {};
        this.gejalaData.forEach(gejala => {
            if (!gejalaByKategori[gejala.kategori]) {
                gejalaByKategori[gejala.kategori] = [];
            }
            gejalaByKategori[gejala.kategori].push(gejala);
        });

        // Render each category
        Object.keys(gejalaByKategori).forEach(kategori => {
            const categoryName = kategori.charAt(0).toUpperCase() + kategori.slice(1);
            const gejalaList = gejalaByKategori[kategori];

            html += `
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-folder me-2"></i>
                        ${categoryName}
                        <span class="badge bg-secondary float-end">${gejalaList.length} gejala</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
        `;

            // Split menjadi 2 kolom
            const midIndex = Math.ceil(gejalaList.length / 2);

            // Kolom kiri
            html += `<div class="col-md-6">`;
            for (let i = 0; i < midIndex; i++) {
                const gejala = gejalaList[i];
                html += `
            <div class="form-check mb-3">
                <input
                    class="form-check-input gejala-checkbox"
                    type="checkbox"
                    value="${gejala.id}"
                    id="gejala_${gejala.id}"
                    name="gejala[]"
                    data-kategori="${kategori}"
                >
                <label class="form-check-label" for="gejala_${gejala.id}">
                    <span class="badge bg-info me-2">${gejala.kode_gejala}</span>
                    ${gejala.deskripsi_gejala}
                </label>
            </div>
            `;
            }
            html += `</div>`;

            // Kolom kanan
            html += `<div class="col-md-6">`;
            for (let i = midIndex; i < gejalaList.length; i++) {
                const gejala = gejalaList[i];
                html += `
            <div class="form-check mb-3">
                <input
                    class="form-check-input gejala-checkbox"
                    type="checkbox"
                    value="${gejala.id}"
                    id="gejala_${gejala.id}"
                    name="gejala[]"
                    data-kategori="${kategori}"
                >
                <label class="form-check-label" for="gejala_${gejala.id}">
                    <span class="badge bg-info me-2">${gejala.kode_gejala}</span>
                    ${gejala.deskripsi_gejala}
                </label>
            </div>
            `;
            }
            html += `</div>`;

            html += `
                    </div>
                </div>
            </div>
        </div>
        `;
        });

        $('#gejalaList').html(html);
    }
    /**
     * Handle form submission
     */
    async handleSubmit(form) {
        // Validasi form terlebih dahulu
        const isFormValid = this.validator.form();

        if (!isFormValid) {
            // Jika gejala error, apply styling ke semua checkbox
            if (this.validator.errorMap['gejala[]']) {
                this.applyGejalaErrorStyling();
            }
            return;
        }

        // Collect data untuk API
        const kondisiLingkungan = this.collectKondisiLingkungan();

        this.showLoading(true);

        try {
            // Process diagnosa via service
            const result = await this.service.prosesDiagnosa(kondisiLingkungan, this.selectedGejala);

            // Hide loading
            this.showLoading(false);

            if (result.success) {
                this.showHasilDiagnosa(result.data);
            }

        } catch (error) {
            this.showLoading(false);
        }
    }
    /**
     * Collect kondisi lingkungan data from form
     */
    collectKondisiLingkungan() {
        const kondisiLingkungan = {};

        const fieldMapping = {
            'suhu_udara': 'suhu_udara',
            'kelembapan_udara': 'kelembapan_udara',
            'ph_tanah': 'ph_tanah',
            'intensitas_cahaya': 'intensitas_cahaya',
            'curah_hujan': 'curah_hujan',
            'kelembapan_tanah': 'kelembapan_tanah'
        };

        Object.keys(fieldMapping).forEach(field => {
            const input = $(`#${field}`);
            if (input.length) {
                const value = input.val();
                kondisiLingkungan[field] = value !== '' ? parseFloat(value) : null;
            }
        });

        return kondisiLingkungan;
    }

    /**
     * Handle gejala checkbox change
     */
    handleGejalaCheck(e) {
        const checkbox = $(e.target);
        const gejalaId = checkbox.val();

        if (checkbox.is(':checked')) {
            this.selectedGejala.push(gejalaId);
        } else {
            const index = this.selectedGejala.indexOf(gejalaId);
            if (index > -1) {
                this.selectedGejala.splice(index, 1);
            }
        }
    }

    /**
     * Show diagnosa results
     */
    showHasilDiagnosa(data) {
        const hasil = data.diagnosa_terbaik;
        const semuaHasil = data.semua_hasil;

        const hasilHtml = this.generateHasilTemplate(hasil, semuaHasil);
        $('#hasilDiagnosa').html(hasilHtml).removeClass('d-none');

        // Scroll to results
        this.scrollToElement('#hasilDiagnosa');
    }

    /**
     * Generate HTML template for results
     */
    generateHasilTemplate(hasil, semuaHasil) {
        return `
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-file-medical me-2"></i>Hasil Diagnosa</h5>
            </div>
            <div class="card-body">
                <!-- Diagnosis Utama -->
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-diagnoses me-2"></i>Diagnosa: ${hasil.penyakit}
                    </h4>
                    <p class="mb-0">
                        <strong>Tingkat Kepercayaan:</strong> ${hasil.tingkat_kepercayaan}
                        <br>
                        <strong>Kode:</strong> ${hasil.kode_penyakit}
                    </p>
                </div>

                <div class="row">
                    <!-- Deskripsi Penyakit -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Deskripsi Penyakit</h6>
                            </div>
                            <div class="card-body">
                                <p>${this.formatText(hasil.deskripsi)}</p>
                                <p class="mb-0"><strong>Faktor Risiko:</strong> ${hasil.faktor_risiko}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi Perawatan -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-prescription-bottle-medical me-2"></i>Rekomendasi Perawatan</h6>
                            </div>
                            <div class="card-body">
                                <p>${this.formatText(hasil.rekomendasi_perawatan)}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pencegahan -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-shield-virus me-2"></i>Tindakan Pencegahan</h6>
                    </div>
                    <div class="card-body">
                        <p>${this.formatText(hasil.tindakan_pencegahan)}</p>
                    </div>
                </div>

                <!-- Semua Hasil -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Semua Kemungkinan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Penyakit</th>
                                        <th>Persentase</th>
                                        <th>Skor Gejala</th>
                                        <th>Skor Lingkungan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${this.generateHasilTableRows(hasil, semuaHasil)}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Button Actions -->
                <div class="text-center mt-4">
                    <button class="btn btn-primary me-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Cetak Hasil
                    </button>
                    <button class="btn btn-success me-2" id="diagnosaLagiBtn">
                        <i class="fas fa-redo me-2"></i>Diagnosa Lagi
                    </button>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Generate table rows for all results
     */
    generateHasilTableRows(hasil, semuaHasil) {
        return semuaHasil.map(item => {
            const persentase = parseFloat(item.persentase);
            let progressBarClass = 'bg-danger';

            if (persentase > 70) progressBarClass = 'bg-success';
            else if (persentase > 40) progressBarClass = 'bg-warning';

            const isBest = item.penyakit === hasil.penyakit;
            const rowClass = isBest ? 'class="table-success"' : '';

            return `
            <tr ${rowClass}>
                <td>${item.penyakit}</td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar ${progressBarClass}"
                             role="progressbar"
                             style="width: ${item.persentase}"
                             aria-valuenow="${persentase}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            ${item.persentase}
                        </div>
                    </div>
                </td>
                <td>${parseFloat(item.skor_gejala).toFixed(2)}</td>
                <td>${parseFloat(item.skor_lingkungan).toFixed(2)}</td>
            </tr>
            `;
        }).join('');
    }

    /**
     * Format text with line breaks
     */
    formatText(text) {
        return (text || '').replace(/\n/g, '<br>');
    }

    /**
     * Reset form for new diagnosa
     */
    resetForm() {
        // Reset form validation
        this.validator.resetForm();
        $('#diagnosaForm')[0].reset();

        // Reset gejala selection
        this.selectedGejala = [];
        $('input[name="gejala[]"]').prop('checked', false);

        // Remove semua custom error styling
        this.removeGejalaErrorStyling();
        $('.form-control').removeClass('is-invalid');

        // Clear error message
        $('#gejalaErrorContainer').empty();

        // Hide results
        $('#hasilDiagnosa').addClass('d-none');

        // Scroll to top
        this.scrollToElement('body');

        // Show success message
        successAlert('Form berhasil direset');
    }

    /**
     * Show loading spinner
     */
    showLoading(show) {
        const submitBtn = $('#submitBtn');
        const loadingSpinner = $('#loadingSpinner');

        if (show) {
            submitBtn.prop('disabled', true);
            loadingSpinner.removeClass('d-none');
        } else {
            submitBtn.prop('disabled', false);
            loadingSpinner.addClass('d-none');
        }
    }

    /**
     * Scroll to element
     */
    scrollToElement(selector, offset = -20) {
        const element = $(selector);
        if (element.length) {
            $('html, body').animate({
                scrollTop: element.offset().top + offset
            }, 500);
        }
    }
}
