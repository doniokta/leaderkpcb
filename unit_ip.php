<?php
session_start(); // Memulai sesi untuk menyimpan pesan

// Koneksi ke database
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username Anda
$password = ""; // Ganti dengan password Anda
$dbname = "dailycheck"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses submit form untuk menambah unit IP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add') {
    $unit_name = $_POST['unit_name'];
    $ip_address = $_POST['ip_address'];

    // Insert data ke dalam tabel unit_ip
    $sql = "INSERT INTO unit_ip (unit_name, ip_address) VALUES ('$unit_name', '$ip_address')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Unit IP '{$unit_name}' telah berhasil ditambahkan."; // Menyimpan pesan sukses di sesi
        header("Location: unit_ip.php"); // Refresh halaman setelah submit
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $conn->error; // Menyimpan pesan error di sesi
    }
}

// Proses untuk menghapus unit IP
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM unit_ip WHERE id = $delete_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Unit IP dengan ID '{$delete_id}' telah dihapus."; // Menyimpan pesan sukses di sesi
    } else {
        $_SESSION['message'] = "Error: " . $conn->error; // Menyimpan pesan error di sesi
    }
    header("Location: unit_ip.php"); // Refresh halaman setelah penghapusan
    exit();
}

// Ambil daftar unit IP untuk ditampilkan
$sql = "SELECT * FROM unit_ip";
$result = $conn->query($sql);

$units = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $units[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit IP Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Menambahkan gaya untuk memperkecil ukuran tombol */
        .btn-action {
            padding: 0.2rem 0.4rem; /* Mengurangi padding tombol */
            font-size: 0.8rem; /* Mengurangi ukuran font tombol */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-5 mb-4">Manage Unit IP</h1>

        <!-- Menampilkan pesan jika ada -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); // Menghapus pesan setelah ditampilkan ?>
            </div>
        <?php endif; ?>

        <!-- Form untuk menambah unit IP -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="unit_name" class="form-label">Unit Name</label>
                <input type="text" id="unit_name" name="unit_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="ip_address" class="form-label">IP Address</label>
                <input type="text" id="ip_address" name="ip_address" class="form-control" required>
            </div>
            <input type="hidden" name="action" value="add"> <!-- Menandai aksi sebagai penambahan -->

<!-- Tombol Kembali ke Dashboard dan Tambah Unit IP -->
<div class="d-flex gap-2"> <!-- Menggunakan gap untuk memberikan jarak minimal antara tombol -->
<button type="submit" class="btn btn-success">Add Unit IP</button>
    <a href="dashboard.php" class="btn btn-secondary">Ke Dashboard</a>
</div>


        <!-- Tabel untuk menampilkan unit IP -->
        <h2 class="mt-5">List of Unit IPs</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Unit Name</th>
                    <th>IP Address</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($units as $unit): ?>
                <tr>
                    <td><?= htmlspecialchars($unit['id']) ?></td>
                    <td><?= htmlspecialchars($unit['unit_name']) ?></td>
                    <td><?= htmlspecialchars($unit['ip_address']) ?></td>
                    <td>
                        <a href="edit_unit_ip.php?id=<?= $unit['id'] ?>" class="btn btn-warning btn-action">Edit</a>
                        <a href="?delete_id=<?= $unit['id'] ?>" class="btn btn-danger btn-action" onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
