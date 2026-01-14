/**
 * Diagnosa Service - Handle semua API calls dan business logic
 */
class DiagnosaService {
    constructor(baseUrl = '') {
        this.baseUrl = baseUrl;
        this.validationRules = {};
    }

    /**
     * Set validation rules dari parameter data untuk jQuery Validate
     */
    getJqueryValidateRules(parameterData) {
        const rules = {};
        const messages = {};

        parameterData.forEach(param => {
            const fieldName = param.nama_parameter.toLowerCase().replace(/ /g, '_');
            const min = parseFloat(param.nilai_ideal_min);
            const max = parseFloat(param.nilai_ideal_max);

            rules[`kondisi_lingkungan[${fieldName}]`] = {
                required: true,
                number: true,
                min: min,
                max: max
            };

            messages[`kondisi_lingkungan[${fieldName}]`] = {
                required: `${param.nama_parameter} harus diisi`,
                number: `${param.nama_parameter} harus berupa angka`,
                min: `${param.nama_parameter} minimal ${min} ${param.satuan}`,
                max: `${param.nama_parameter} maksimal ${max} ${param.satuan}`
            };
        });

        return { rules, messages };
    }

    /**
     * Get all gejala data
     */
    async getGejala() {
        try {
            const response = await $.ajax({
                url: `${this.baseUrl}/naive-bayes/gejala/`,
                method: 'GET'
            });

            if (response.code === 200) {
                return { success: true, data: response.data };
            } else {
                errorAlert();
                return { success: false, message: 'Gagal memuat data gejala' };
            }
        } catch (error) {
            errorAlert();
            return { success: false, message: 'Gagal memuat data gejala' };
        }
    }

    /**
     * Get all parameter lingkungan data
     */
    async getParameterLingkungan() {
        try {
            const response = await $.ajax({
                url: `${this.baseUrl}/naive-bayes/parameter-lingkungan/`,
                method: 'GET'
            });

            if (response.code === 200) {
                return { success: true, data: response.data };
            } else {
                errorAlert();
                return { success: false, message: 'Gagal memuat data parameter' };
            }
        } catch (error) {
            errorAlert();
            return { success: false, message: 'Gagal memuat data parameter' };
        }
    }

    /**
     * Process diagnosa
     */
    async prosesDiagnosa(kondisiLingkungan, gejalaDipilih) {
        try {
            // Prepare data
            const requestData = {
                kondisi_lingkungan: {
                    suhu_udara: parseFloat(kondisiLingkungan.suhu_udara),
                    kelembapan_udara: parseFloat(kondisiLingkungan.kelembapan_udara),
                    ph_tanah: parseFloat(kondisiLingkungan.ph_tanah),
                    intensitas_cahaya: parseFloat(kondisiLingkungan.intensitas_cahaya),
                    curah_hujan: parseFloat(kondisiLingkungan.curah_hujan),
                    kelembapan_tanah: parseFloat(kondisiLingkungan.kelembapan_tanah)
                },
                gejala: gejalaDipilih
            };

            const response = await $.ajax({
                url: `${this.baseUrl}/naive-bayes/diagnosa/create`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            });

            if (response.success) {
                return {
                    success: true,
                    data: response.data,
                    message: 'Diagnosa berhasil'
                };
            } else {
                return {
                    success: false,
                    message: response.message
                };
            }

        } catch (error) {
            console.error('Full error object:', error);

            let errorMessage = 'Terjadi kesalahan pada server';
            let shouldShowAlert = true;
            let alertType = 'error'; // default

            if (error.status === 422) {
                setTimeout(() => {
                    warningAlert(errorMessage);
                }, 100);

            } else if (error.status === 500) {
                setTimeout(() => {
                    errorAlert();
                }, 100);
            } else if (error.statusText) {
                errorMessage = error.statusText;
            }

            return {
                success: false,
                statusCode: error.status,
            };
        }
    }

    /**
     * Validate gejala selection
     */
    validateGejalaSelection(selectedGejala) {
        return selectedGejala.length > 0;
    }
}
