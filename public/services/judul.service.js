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

    initDataTable() {
        if (!$.fn.dataTable.isDataTable('#judulTable')) {
            $('#judulTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    emptyTable: `
                        <div class="py-5 text-muted">
                            <i class="fa-solid fa-folder-open fa-3x mb-3"></i><br>
                            Belum ada data pengajuan judul.
                        </div>`
                }
            });
        }
    }

    async getAllData() {
        try {
            const response = await this.ajaxRequest(`${appUrl}/sitasi/pengajuan/`, 'GET');
            return response.data || [];
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
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
