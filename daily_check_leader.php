<?php
// Koneksi ke database
session_start(); // Memulai session untuk menyimpan pesan
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username Anda
$password = ""; // Ganti dengan password Anda
$dbname = "dailycheck"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi variabel pencarian
$search = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Ambil data dari tabel daily_check_leader, urutkan berdasarkan tanggal
$sql = "SELECT dc.*, u.unit_name FROM daily_check_leader dc INNER JOIN unit_ip u ON dc.unit_id = u.id";
if (!empty($search)) {
    $sql .= " WHERE dc.engineer LIKE '%$search%' OR u.unit_name LIKE '%$search%' OR dc.date LIKE '%$search%'";
}
$sql .= " ORDER BY dc.date ASC";

$result = $conn->query($sql);

$daily_checks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $daily_checks[] = $row;
    }
}

// Ekspor ke Excel
if (isset($_GET['export'])) {
    $date = date('Y-m-d'); // Mendapatkan tanggal saat ini
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="Daily_Check_Leader_' . $date . '.xls"');

    // Output header
    echo "Unit\tEngineer\tDate\tUMPC\tGPS\tVHMS\tPLM\tLAN\tStatus\n";

    // Output data
    foreach ($daily_checks as $check) {
        echo htmlspecialchars($check['unit_name']) . "\t" .
             htmlspecialchars($check['engineer']) . "\t" .
             htmlspecialchars($check['date']) . "\t" .
             htmlspecialchars($check['umpc']) . "\t" .
             htmlspecialchars($check['gps']) . "\t" .
             htmlspecialchars($check['vhms']) . "\t" .
             htmlspecialchars($check['plm']) . "\t" .
             htmlspecialchars($check['lan']) . "\t" .
             htmlspecialchars($check['status']) . "\n";
    }
    exit();
}

// Hapus data
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM daily_check_leader WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Data berhasil dihapus!";
    } else {
        $_SESSION['message'] = "Gagal menghapus data!";
    }
    $stmt->close();
    header("Location: daily_check_leader.php");
    exit();
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Check Leader Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 10px; /* Spacing between buttons */
        }
        .search-form {
            display: flex;
            align-items: center; /* Center the search input vertically */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-5 mb-4">Daily Check Leader Data</h1>

        <!-- Tampilkan pesan jika ada -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); // Hapus pesan setelah ditampilkan ?>
        <?php endif; ?>

        <!-- Search and Action Buttons Container -->
        <div class="search-container">
            <!-- Button to add new daily check, export to Excel, and go to graph -->
            <div class="action-buttons">
                <a href="tambah_daily_check_leader.php" class="btn btn-primary">Tambah Daily Check Leader</a>
                <a href="daily_check_leader.php?export=true" class="btn btn-success">Export to Excel</a>
                <a href="daily_check_graph.php" class="btn btn-info">Lihat Grafik</a> <!-- Tombol untuk mengakses grafik -->
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a> <!-- Tombol Kembali ke Dashboard -->
            </div>

            <!-- Search Form -->
            <form method="GET" class="search-form">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by Engineer, Unit, or Date" value="<?= htmlspecialchars($search) ?>" style="width: 250px;">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <!-- Table to display data -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Engineer</th>
                    <th>Date</th>
                    <th>UMPC</th>
                    <th>GPS</th>
                    <th>VHMS</th>
                    <th>PLM</th>
                    <th>LAN</th>
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daily_checks)): ?>
                    <?php foreach ($daily_checks as $check): ?>
                    <tr>
                        <td><?= htmlspecialchars($check['unit_name']) ?></td>
                        <td><?= htmlspecialchars($check['engineer']) ?></td>
                        <td><?= htmlspecialchars($check['date']) ?></td>
                        <td><?= htmlspecialchars($check['umpc']) ?></td>
                        <td><?= htmlspecialchars($check['gps']) ?></td>
                        <td><?= htmlspecialchars($check['vhms']) ?></td>
                        <td><?= htmlspecialchars($check['plm']) ?></td>
                        <td><?= htmlspecialchars($check['lan']) ?></td>
                        <td><?= htmlspecialchars($check['status']) ?></td>
                        <td>
                            <a href="edit_daily_check_leader.php?id=<?= $check['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="daily_check_leader.php?delete_id=<?= $check['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
