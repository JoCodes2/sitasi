class judulService {
    ajaxRequest(url, method, data = null) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url,
                method,
                data,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => resolve(response),
                error: (error) => reject(error),
            });
        });
    }

    async getAllData() {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan/`, 'GET');
            let data = response.data;

            // Sorting manual: Tanggal terbaru ke terlama
            data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            return data;
        } catch (error) {
            console.error('Error:', error);
            this.renderEmptyState();
            return [];
        }
    }

    renderEmptyState() {
        $("#judulBody").html(`
            <tr>
                <td colspan="9" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada judul yang diajukan mahasiswa.</p>
                    </div>
                </td>
            </tr>
        `);
    }

    async getDataById(id) {
        return await this.ajaxRequest(`${appUrl}/sitasi/pengajuan/get/${id}`, 'GET');
    }

    extractNimFromEmail(email) {
        // Contoh: jika email mengandung nim, extract angka
        const match = email.match(/\d+/);
        return match ? match[0] : '-';
    }

    // Helper untuk extract angkatan dari NIM
    extractAngkatanFromNim(nim) {
        if (nim && nim.length >= 4) {
            return '20' + nim.substring(0, 2); // Asumsi NIM: 2100001 -> angkatan 2021
        }
        return '-';
    }

    // Format status gelombang
    formatStatusGelombang(isAktif) {
        return isAktif == 1
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Tidak Aktif</span>';
    }

    // Format semester
    formatSemester(semester) {
        const semesterMap = {
            'ganjil': 'Ganjil',
            'genap': 'Genap'
        };
        return semesterMap[semester] || semester;
    }
    async updateStatusJudul(detailId, status) {
        const fd = new FormData();
        fd.append('status', status);

        return await this.ajaxRequest(
            `${appUrl}/sitasi/pengajuan/detail/${detailId}/status`,
            'POST',
            fd
        );
    }

}

export default judulService;
