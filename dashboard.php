<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f4f8, #e2e7ef);
        }
        .dashboard-button {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .dashboard-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .button-icon {
            font-size: 2rem; /* Increase icon size */
            margin-bottom: 10px; /* Space between icon and text */
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="display-4 text-center mb-5">Dashboard</h1>
        <div class="row">
            <div class="col-md-4">
                <a href="daily_check_leader.php" class="btn btn-success dashboard-button w-100 py-4 mb-3">
                    <i class="fas fa-check-circle button-icon"></i>
                    Daily Check Leader
                </a>
            </div>
            <div class="col-md-4">
                <a href="takeup_leader.php" class="btn btn-warning dashboard-button w-100 py-4 mb-3">
                    <i class="fas fa-arrow-up button-icon"></i>
                    Takeup Leader
                </a>
            </div>
            <div class="col-md-4">
                <a href="stock_material_leader.php" class="btn btn-danger dashboard-button w-100 py-4 mb-3">
                    <i class="fas fa-box-open button-icon"></i>
                    Stock Material Leader
                </a>
            </div>
            <div class="col-md-4">
                <a href="unit_ip.php" class="btn btn-primary dashboard-button w-100 py-4 mb-3">
                    <i class="fas fa-network-wired button-icon"></i>
                    Unit & IP
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
