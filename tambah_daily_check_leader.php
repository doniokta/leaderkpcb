<?php
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

// Ambil daftar unit untuk dropdown
$unit_sql = "SELECT * FROM unit_ip";
$unit_result = $conn->query($unit_sql);

// Simpan semua unit dalam array untuk digunakan di JavaScript
$units = [];
while ($unit = $unit_result->fetch_assoc()) {
    $units[] = $unit;
}

// Proses submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unit_id = $_POST['unit_id'];
    $engineer = $_POST['engineer'];
    $date = $_POST['date'];
    $umpc = $_POST['umpc'];
    $gps = $_POST['gps'];
    $vhms = $_POST['vhms'];
    $plm = $_POST['plm'];
    $lan = $_POST['lan'];
    $status = $_POST['status'];

    // Insert data ke dalam tabel daily_check_leader
    $sql = "INSERT INTO daily_check_leader (unit_id, engineer, date, umpc, gps, vhms, plm, lan, status) 
            VALUES ('$unit_id', '$engineer', '$date', '$umpc', '$gps', '$vhms', '$plm', '$lan', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: daily_check_leader.php"); // Redirect ke daily_check_leader.php setelah submit
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Daily Check Leader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #unitList {
            border: 1px solid #ddd;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            z-index: 1000;
            background: white;
            width: calc(100% - 1rem); /* Adjust width to align with input */
        }
        .unit-item {
            padding: 10px;
            cursor: pointer;
        }
        .unit-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-5 mb-4">Tambah Daily Check Leader</h1>

        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3 position-relative">
                        <label for="unit_search" class="form-label">Unit</label>
                        <input type="text" id="unit_search" class="form-control" placeholder="Ketik ID atau Nama Unit" onkeyup="filterUnits()" required>
                        <div id="unitList" class="list-group"></div>
                        <input type="hidden" name="unit_id" id="unit_id" required>
                    </div>

                    <div class="mb-3">
                        <label for="engineer" class="form-label">Engineer</label>
                        <select id="engineer" name="engineer" class="form-select" required>
                            <option value="Doni">Doni</option>
                            <option value="Saeful">Saeful</option>
                            <option value="Syalfin">Syalfin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="umpc" class="form-label">UMPC</label>
                        <select id="umpc" name="umpc" class="form-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Tidak Normal">Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="gps" class="form-label">GPS</label>
                        <select id="gps" name="gps" class="form-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Tidak Normal">Tidak Normal</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="vhms" class="form-label">VHMS</label>
                        <select id="vhms" name="vhms" class="form-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Tidak Normal">Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="plm" class="form-label">PLM</label>
                        <select id="plm" name="plm" class="form-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Tidak Normal">Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="lan" class="form-label">LAN</label>
                        <select id="lan" name="lan" class="form-select" required>
                            <option value="Normal">Normal</option>
                            <option value="Tidak Normal">Tidak Normal</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="Close">Close</option>
                            <option value="Open">Open</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="daily_check_leader.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script>
        // Unit data from PHP to JavaScript
        const units = <?= json_encode($units); ?>;

        function filterUnits() {
            const input = document.getElementById('unit_search').value.toLowerCase();
            const unitList = document.getElementById('unitList');
            unitList.innerHTML = ''; // Clear previous results
            unitList.style.display = 'none'; // Hide list initially

            if (input) {
                const filteredUnits = units.filter(unit => 
                    unit.unit_name.toLowerCase().includes(input) || unit.id.toString().includes(input)
                );

                if (filteredUnits.length > 0) {
                    filteredUnits.forEach(unit => {
                        const item = document.createElement('div');
                        item.className = 'unit-item list-group-item list-group-item-action';
                        item.textContent = `${unit.unit_name} (ID: ${unit.id})`;
                        item.onclick = function() {
                            document.getElementById('unit_search').value = unit.unit_name; // Set the input value
                            document.getElementById('unit_id').value = unit.id; // Set the hidden unit ID
                            unitList.style.display = 'none'; // Hide the list after selection
                        };
                        unitList.appendChild(item);
                    });
                    unitList.style.display = 'block'; // Show the list
                }
            }
        }

        // Hide the unit list when clicking outside
        document.addEventListener('click', function(event) {
            const unitList = document.getElementById('unitList');
            if (!unitList.contains(event.target) && event.target.id !== 'unit_search') {
                unitList.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
