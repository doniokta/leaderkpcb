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

// Proses submit form untuk mengupdate unit IP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $unit_name = $_POST['unit_name'];
    $ip_address = $_POST['ip_address'];

    // Update data ke dalam tabel unit_ip
    $sql = "UPDATE unit_ip SET unit_name='$unit_name', ip_address='$ip_address' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Unit IP '{$unit_name}' telah berhasil diperbarui."; // Menyimpan pesan sukses di sesi
        header("Location: unit_ip.php"); // Refresh halaman setelah submit
        exit();
    } else {
        $_SESSION['message'] = "Error: " . $conn->error; // Menyimpan pesan error di sesi
    }
}

// Ambil data unit IP untuk diedit
$id = $_GET['id'];
$sql = "SELECT * FROM unit_ip WHERE id='$id'";
$result = $conn->query($sql);
$unit = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Unit IP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-5 mb-4">Edit Unit IP</h1>

        <!-- Menampilkan pesan jika ada -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); // Menghapus pesan setelah ditampilkan ?>
            </div>
        <?php endif; ?>

        <!-- Form untuk mengedit unit IP -->
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($unit['id']) ?>">
            <div class="mb-3">
                <label for="unit_name" class="form-label">Unit Name</label>
                <input type="text" id="unit_name" name="unit_name" class="form-control" value="<?= htmlspecialchars($unit['unit_name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ip_address" class="form-label">IP Address</label>
                <input type="text" id="ip_address" name="ip_address" class="form-control" value="<?= htmlspecialchars($unit['ip_address']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Update Unit IP</button>
        </form>
    </div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
