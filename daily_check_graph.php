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

// Mengambil data untuk grafik
$data = [];
$labels = [];
$sql = "SELECT date, COUNT(*) as total_checks FROM daily_check_leader GROUP BY date ORDER BY date ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['date'];
        $data[] = (int)$row['total_checks'];
    }
}

// Mengambil semua unit
$all_units_query = "SELECT id, unit_name FROM unit_ip";
$all_units_result = $conn->query($all_units_query);

// Mengambil unit yang telah diperiksa
$checked_units_query = "SELECT DISTINCT unit_id FROM daily_check_leader";
$checked_units_result = $conn->query($checked_units_query);

// Menyimpan ID unit yang telah diperiksa
$checked_units = [];
if ($checked_units_result->num_rows > 0) {
    while ($row = $checked_units_result->fetch_assoc()) {
        $checked_units[] = $row['unit_id'];
    }
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Check Graph</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #e2e7ef);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <!-- Action buttons at the top -->
        <div class="action-buttons">
            <a href="daily_check_leader.php" class="btn btn-success">Back to Daily Check Leader</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <h1 class="display-4 text-center mb-5">Daily Check Dashboard</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Daily Check Overview</h5>
                <canvas id="dailyCheckChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Collapsible sections for Checked and Not Checked Units -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white bg-success">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#checkedUnits" aria-expanded="true" aria-controls="checkedUnits">
                                Units Checked
                            </button>
                        </h5>
                    </div>
                    <div id="checkedUnits" class="collapse show">
                        <div class="card-body">
                            <ul class="list-group">
                                <?php while ($unit = $all_units_result->fetch_assoc()): ?>
                                    <?php if (in_array($unit['id'], $checked_units)): ?>
                                        <li class="list-group-item list-group-item-success"><?= htmlspecialchars($unit['unit_name']) ?></li>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-white bg-danger">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <button class="btn btn-link text-white" type="button" data-bs-toggle="collapse" data-bs-target="#notCheckedUnits" aria-expanded="true" aria-controls="notCheckedUnits">
                                Units Not Checked
                            </button>
                        </h5>
                    </div>
                    <div id="notCheckedUnits" class="collapse show">
                        <div class="card-body">
                            <ul class="list-group">
                                <?php
                                // Reset pointer to fetch units again
                                $all_units_result->data_seek(0);
                                while ($unit = $all_units_result->fetch_assoc()): ?>
                                    <?php if (!in_array($unit['id'], $checked_units)): ?>
                                        <li class="list-group-item list-group-item-danger"><?= htmlspecialchars($unit['unit_name']) ?></li>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('dailyCheckChart').getContext('2d');
        const dailyCheckChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Daily Checks',
                    data: <?php echo json_encode($data); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Checks'
                        }
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
