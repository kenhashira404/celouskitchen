<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Fetch data from the database for earnings
$earningQuery = "SELECT MONTH(placed_on) AS month, SUM(total_price) AS total_earnings FROM orders WHERE payment_status = 'completed' GROUP BY MONTH(placed_on)";
$earningResult = $conn->query($earningQuery);
$earningData = $earningResult->fetchAll(PDO::FETCH_ASSOC);

// Fetch data from the database for orders count
$ordersQuery = "SELECT MONTH(placed_on) AS month, COUNT(id) AS order_count FROM orders WHERE payment_status = 'completed' GROUP BY MONTH(placed_on)";
$ordersResult = $conn->query($ordersQuery);
$ordersData = $ordersResult->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for the chart
$months = [];
$totalEarnings = [];
$orderCounts = [];

foreach ($earningData as $row) {
    $months[] = date('F', mktime(0, 0, 0, $row['month'], 1));
    $totalEarnings[] = $row['total_earnings'];
}

foreach ($ordersData as $row) {
    $orderCounts[] = $row['order_count'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Chart.js CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS file link -->
    <style><?php include '../css/admin_style.css' ?></style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Admin dashboard section starts  -->

<section class="dashboard">
    <h1 class="heading">Chart</h1>

    <!-- Chart container -->
    <div class="chart-container">
        <canvas id="combinedChart" width="400" height="200"></canvas>
    </div>
</section>

<!-- Admin dashboard section ends -->

<!-- Custom JS file link -->
<script src="../js/admin_script.js"></script>

<!-- Render the combined chart -->
<script>
    var ctx = document.getElementById('combinedChart').getContext('2d');
    var combinedChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [
                {
                    label: 'Earnings',
                    data: <?php echo json_encode($totalEarnings); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    yAxisID: 'y-axis-earnings'
                },
                {
                    label: 'Orders',
                    data: <?php echo json_encode($orderCounts); ?>,
                    type: 'line',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false,
                    yAxisID: 'y-axis-orders'
                }
            ]
        },
        options: {
            scales: {
                y: [
                    {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        id: 'y-axis-earnings'
                    },
                    {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        id: 'y-axis-orders'
                    }
                ]
            }
        }
    });
</script>

</body>
</html>
