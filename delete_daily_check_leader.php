<?php
session_start(); // Mulai session untuk pemberitahuan
$servername = "localhost"; // Ganti dengan server Anda
$username = "root"; // Ganti dengan username Anda
$password = ""; // Ganti dengan password Anda
$dbname = "dailycheck"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $delete_sql = "DELETE FROM daily_check_leader WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
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
