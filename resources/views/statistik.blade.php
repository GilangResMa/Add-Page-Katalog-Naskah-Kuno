<?php
include '../config/database.php';
$conn = mysqli_connect('localhost', 'root', '', 'projectKP');
if (!$conn) {
    print "Error";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="margin: 20px 0;">
        <label for="filterRuangan">Ruangan: </label>
        <select id="filterRuangan" onchange="updateChart()">
            <option value="">Semua Ruangan</option>
            <?php
            $ruanganQuery = mysqli_query($conn, "SELECT DISTINCT ruangan FROM pengunjung ORDER BY ruangan");
            while ($row = mysqli_fetch_array($ruanganQuery)) {
                echo "<option value='{$row['ruangan']}'>{$row['ruangan']}</option>";
            }
            ?>
        </select>

        <label for="filterBulan" style="margin-left: 20px;">Bulan: </label>
        <select id="filterBulan" onchange="updateChart()">
            <option value="">Semua Bulan</option>
            <?php
            $bulanQuery = mysqli_query($conn, "SELECT DISTINCT bulanTahun FROM pengunjung ORDER BY 
        CASE 
            WHEN bulanTahun LIKE 'Januari%' THEN 1
            WHEN bulanTahun LIKE 'Februari%' THEN 2
            WHEN bulanTahun LIKE 'Maret%' THEN 3
            WHEN bulanTahun LIKE 'April%' THEN 4
            WHEN bulanTahun LIKE 'Mei%' THEN 5
            WHEN bulanTahun LIKE 'Juni%' THEN 6
            WHEN bulanTahun LIKE 'Juli%' THEN 7
            WHEN bulanTahun LIKE 'Agustus%' THEN 8
            WHEN bulanTahun LIKE 'September%' THEN 9
            WHEN bulanTahun LIKE 'Oktober%' THEN 10
            WHEN bulanTahun LIKE 'November%' THEN 11
            WHEN bulanTahun LIKE 'Desember%' THEN 12
            ELSE 13
        END");
            while ($row = mysqli_fetch_array($bulanQuery)) {
                echo "<option value='{$row['bulanTahun']}'>{$row['bulanTahun']}</option>";
            }
            ?>
        </select>
    </div>

    <!-- Chart Container -->
    <div style="width: 90%; margin: 20px auto;">
        <canvas id="pengunjungChart"></canvas>
    </div>

    <!-- Data Table (Optional - dapat disembunyikan) -->
    <div style="margin-top: 30px;">
        <h3>Detail Data</h3>
        <table border="1" cellpadding="5" cellspacing="0" id="dataTable">
            <tr>
                <th>No</th>
                <th>Ruangan</th>
                <th>Bulan</th>
                <th>Pelajar L</th>
                <th>Pelajar P</th>
                <th>Mahasiswa L</th>
                <th>Mahasiswa P</th>
                <th>Umum L</th>
                <th>Umum P</th>
                <th>Total</th>
            </tr>
            <?php
            $pengunjung = "SELECT * FROM pengunjung ORDER BY 
            CASE 
                WHEN bulanTahun LIKE 'Januari%' THEN 1
                WHEN bulanTahun LIKE 'Februari%' THEN 2
                WHEN bulanTahun LIKE 'Maret%' THEN 3
                WHEN bulanTahun LIKE 'April%' THEN 4
                WHEN bulanTahun LIKE 'Mei%' THEN 5
                WHEN bulanTahun LIKE 'Juni%' THEN 6
                WHEN bulanTahun LIKE 'Juli%' THEN 7
                WHEN bulanTahun LIKE 'Agustus%' THEN 8
                WHEN bulanTahun LIKE 'September%' THEN 9
                WHEN bulanTahun LIKE 'Oktober%' THEN 10
                WHEN bulanTahun LIKE 'November%' THEN 11
                WHEN bulanTahun LIKE 'Desember%' THEN 12
                ELSE 13
            END,
            ruangan";
            $query = mysqli_query($conn, $pengunjung);

            $no = 1;
            $allData = [];
            while ($data = mysqli_fetch_array($query)) {
                $idP = $data['idPengunjung'];
                $ruangan = $data['ruangan'];
                $bulan = $data['bulanTahun'];
                $pelajarL = $data['pelajarL'];
                $pelajarP = $data['pelajarP'];
                $mahasiswaL = $data['mahasiswaL'];
                $mahasiswaP = $data['mahasiswaP'];
                $umumL = $data['umumL'];
                $umumP = $data['umumP'];

                $total = $pelajarL + $pelajarP + $mahasiswaL + $mahasiswaP + $umumL + $umumP;

                // Store data for JavaScript
                $allData[] = [
                    'idP' => $idP,
                    'ruangan' => $ruangan,
                    'bulan' => $bulan,
                    'pelajarL' => $pelajarL,
                    'pelajarP' => $pelajarP,
                    'mahasiswaL' => $mahasiswaL,
                    'mahasiswaP' => $mahasiswaP,
                    'umumL' => $umumL,
                    'umumP' => $umumP,
                    'total' => $total
                ];

                echo "
                <tr class='data-row' data-ruangan='$ruangan' data-bulan='$bulan' align='centered'>
                    <td>$no</td>
                    <td>$ruangan</td>
                    <td>$bulan</td>    
                    <td>$pelajarL</td>
                    <td>$pelajarP</td>
                    <td>$mahasiswaL</td>
                    <td>$mahasiswaP</td>
                    <td>$umumL</td>
                    <td>$umumP</td>
                    <td><strong>$total</strong></td>
                </tr>";

                $no++;
            }
            ?>
        </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari PHP
    const allData = <?php echo json_encode($allData); ?>;
    let chart = null;

    // Konfigurasi kategori pengunjung
    const categories = [{
            key: 'pelajarL',
            label: 'Pelajar L',
            color: 'rgba(54, 162, 235, 0.7)'
        },
        {
            key: 'pelajarP',
            label: 'Pelajar P',
            color: 'rgba(255, 99, 132, 0.7)'
        },
        {
            key: 'mahasiswaL',
            label: 'Mahasiswa L',
            color: 'rgba(75, 192, 192, 0.7)'
        },
        {
            key: 'mahasiswaP',
            label: 'Mahasiswa P',
            color: 'rgba(255, 159, 64, 0.7)'
        },
        {
            key: 'umumL',
            label: 'Umum L',
            color: 'rgba(153, 102, 255, 0.7)'
        },
        {
            key: 'umumP',
            label: 'Umum P',
            color: 'rgba(255, 206, 86, 0.7)'
        }
    ];

    const monthOrder = {
        'Januari': 1,
        'Februari': 2,
        'Maret': 3,
        'April': 4,
        'Mei': 5,
        'Juni': 6,
        'Juli': 7,
        'Agustus': 8,
        'September': 9,
        'Oktober': 10,
        'November': 11,
        'Desember': 12
    };

    function getMonthOrder(bulanStr) {
        for (let bulan in monthOrder) {
            if (bulanStr.includes(bulan)) return monthOrder[bulan];
        }
        return 99;
    }

    function updateChart() {
        const filterRuangan = document.getElementById('filterRuangan').value;
        const filterBulan = document.getElementById('filterBulan').value;

        // Filter dan sort data
        let filteredData = allData
            .filter(item => (!filterRuangan || item.ruangan === filterRuangan) &&
                (!filterBulan || item.bulan === filterBulan))
            .sort((a, b) => {
                const diff = getMonthOrder(a.bulan) - getMonthOrder(b.bulan);
                return diff !== 0 ? diff : a.ruangan.localeCompare(b.ruangan);
            });

        // Update table visibility
        document.querySelectorAll('.data-row').forEach(row => {
            const match = (!filterRuangan || row.dataset.ruangan === filterRuangan) &&
                (!filterBulan || row.dataset.bulan === filterBulan);
            row.style.display = match ? '' : 'none';
        });

        // Generate labels berdasarkan filter
        const labels = filteredData.map(item => {
            if (filterRuangan && filterBulan) return `${item.ruangan} - ${item.bulan}`;
            if (filterRuangan) return item.bulan;
            if (filterBulan) return item.ruangan;
            return `${item.ruangan} - ${item.bulan}`;
        });

        // Generate datasets dengan loop
        const datasets = categories.map(cat => ({
            label: cat.label,
            data: filteredData.map(item => item[cat.key]),
            backgroundColor: cat.color
        }));

        // Dynamic X-axis title
        const xAxisTitle = filterRuangan && filterBulan ? `${filterRuangan} - ${filterBulan}` :
            filterRuangan ? `${filterRuangan}` :
            filterBulan ? `${filterBulan}` : 'Ruangan - Bulan';

        // Update chart
        if (chart) chart.destroy();

        chart = new Chart(document.getElementById('pengunjungChart'), {
            type: 'bar',
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Statistik Pengunjung per Ruangan dan Bulan',
                        font: {
                            size: 18
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        title: {
                            display: true,
                            text: xAxisTitle
                        }
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Pengunjung'
                        }
                    }
                }
            }
        });
    }

    window.onload = updateChart;
</script>

</html>

<?php
mysqli_close($conn);
?>