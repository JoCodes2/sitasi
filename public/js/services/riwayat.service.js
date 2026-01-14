// RIWAYAT SERVICE — TAMPILAN SUPER RAPIH
class riwayatService {
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
        if ($.fn.dataTable.isDataTable('#riwayatTabel')) {
            $('#riwayatTabel').DataTable().clear().destroy();
        }

        $("#riwayatTabel tbody").empty();

        try {
            const responseData = await this.ajaxRequest(`${appUrl}/naive-bayes/diagnosa/riwayat`, 'GET');

            const data = responseData.data;
            let tableBody = "";

            data.forEach((item, index) => {

                // 1. KONDISI LINGKUNGAN DINAMIS
                let kondisi = `<ul>`;
                Object.entries(item.kondisi_lingkungan).forEach(([key, value]) => {
                    const label = key.replace(/_/g, " ").replace(/\b\w/g, char => char.toUpperCase());
                    kondisi += `<li><b>${label}</b>: ${value}</li>`;
                });
                kondisi += `</ul>`;

                // 2. GEJALA
                const gejala = `
                    <ul>
                        ${item.gejala.map(g => `<li>${g}</li>`).join("")}
                    </ul>
                `;

                // 3. REKOMENDASI PERAWATAN
                const perawatanList = `
                    <ul>
                        ${item.rekomendasi_perawatan
                        .split("\n")
                        .map(p => `<p>${p}</p>`)
                        .join("")}
                    </ul>
                `;

                // 4. REKOMENDASI PENCEGAHAN
                const pencegahanList = `
                    <ul>
                        ${item.rekomendasi_pencegahan
                        .split("\n")
                        .map(p => `<p>${p}</p>`)
                        .join("")}
                    </ul>
                `;

                // 5. SUSUN TABEL
                tableBody += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.tanggal_diagnosa}</td>
                        <td>${kondisi}</td>
                        <td>${gejala}</td>
                        <td><span class="nama-penyakit">${item.nama_penyakit}</span></td>
                        <td><span class="percent-text">${item.tingkat_kepercayaan}</span></td>
                        <td>${perawatanList}</td>
                        <td>${pencegahanList}</td>
                        <td>${item.catatan_tambahan || '-'}</td>
                    </tr>
                `;
            });

            $("#riwayatTabel tbody").html(tableBody);

            // DATATABLE
            $('#riwayatTabel').DataTable({
                paging: true,
                searching: true,
                responsive: true,
                order: [[0, 'asc']],
                pageLength: 10,
            });

        } catch (error) {
            console.error("Error fetching data:", error);
        }
    }
}

export default riwayatService;
