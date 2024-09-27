<?php
// Koneksi ke database
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

// Ambil data untuk di edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM daily_check_leader WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $daily_check = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses form edit
    $unit_id = $_POST['unit_id'];
    $engineer = $_POST['engineer'];
    $date = $_POST['date'];
    $umpc = $_POST['umpc'];
    $gps = $_POST['gps'];
    $vhms = $_POST['vhms'];
    $plm = $_POST['plm'];
    $lan = $_POST['lan'];
    $status = $_POST['status'];

    // Update query
    $update_sql = "UPDATE daily_check_leader SET unit_id = ?, engineer = ?, date = ?, umpc = ?, gps = ?, vhms = ?, plm = ?, lan = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("issssssssi", $unit_id, $engineer, $date, $umpc, $gps, $vhms, $plm, $lan, $status, $id);
    $stmt->execute();
    $stmt->close();

    // Set session message
    $_SESSION['message'] = "Data berhasil diupdate!";
    header("Location: daily_check_leader.php");
    exit();
}

// Ambil daftar unit untuk dropdown
$unit_sql = "SELECT * FROM unit_ip";
$unit_result = $conn->query($unit_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Daily Check Leader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-5 mb-4">Edit Daily Check Leader</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); // Hapus pesan setelah ditampilkan ?>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="unit_id" class="form-label">Unit</label>
                        <select id="unit_id" name="unit_id" class="form-select" required>
                            <option value="">Pilih Unit</option>
                            <?php while ($unit = $unit_result->fetch_assoc()): ?>
                                <option value="<?= $unit['id'] ?>" <?= ($unit['id'] == $daily_check['unit_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($unit['unit_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="engineer" class="form-label">Engineer</label>
                        <select id="engineer" name="engineer" class="form-select" required>
                            <option value="Doni" <?= ($daily_check['engineer'] == 'Doni') ? 'selected' : '' ?>>Doni</option>
                            <option value="Saeful" <?= ($daily_check['engineer'] == 'Saeful') ? 'selected' : '' ?>>Saeful</option>
                            <option value="Syalfin" <?= ($daily_check['engineer'] == 'Syalfin') ? 'selected' : '' ?>>Syalfin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($daily_check['date']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="umpc" class="form-label">UMPC</label>
                        <select id="umpc" name="umpc" class="form-select" required>
                            <option value="Normal" <?= ($daily_check['umpc'] == 'Normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="Tidak Normal" <?= ($daily_check['umpc'] == 'Tidak Normal') ? 'selected' : '' ?>>Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gps" class="form-label">GPS</label>
                        <select id="gps" name="gps" class="form-select" required>
                            <option value="Normal" <?= ($daily_check['gps'] == 'Normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="Tidak Normal" <?= ($daily_check['gps'] == 'Tidak Normal') ? 'selected' : '' ?>>Tidak Normal</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="vhms" class="form-label">VHMS</label>
                        <select id="vhms" name="vhms" class="form-select" required>
                            <option value="Normal" <?= ($daily_check['vhms'] == 'Normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="Tidak Normal" <?= ($daily_check['vhms'] == 'Tidak Normal') ? 'selected' : '' ?>>Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="plm" class="form-label">PLM</label>
                        <select id="plm" name="plm" class="form-select" required>
                            <option value="Normal" <?= ($daily_check['plm'] == 'Normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="Tidak Normal" <?= ($daily_check['plm'] == 'Tidak Normal') ? 'selected' : '' ?>>Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lan" class="form-label">LAN</label>
                        <select id="lan" name="lan" class="form-select" required>
                            <option value="Normal" <?= ($daily_check['lan'] == 'Normal') ? 'selected' : '' ?>>Normal</option>
                            <option value="Tidak Normal" <?= ($daily_check['lan'] == 'Tidak Normal') ? 'selected' : '' ?>>Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="Close" <?= ($daily_check['status'] == 'Close') ? 'selected' : '' ?>>Close</option>
                            <option value="Open" <?= ($daily_check['status'] == 'Open') ? 'selected' : '' ?>>Open</option>
                            <option value="Pending" <?= ($daily_check['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="daily_check_leader.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
