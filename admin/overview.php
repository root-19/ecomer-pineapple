<?php
// Start the session
session_start();

// Display errors for debugging (remove this in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include_once "../../Controller/Database/Database.php"; 
$database = new Database(); 
$conn = $database->connect();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Access denied.";
}

// Fetching total orders, total sales, and total visitors
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM `sold`";
$totalSalesQuery = "SELECT SUM(`price` * `quantity`) AS total_sales FROM `sold`";
$totalVisitorsQuery = "SELECT COUNT(*) AS total_visitors FROM `visitors`"; // Assuming 'visitors' is the table name

// Execute total orders query
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = $totalOrdersResult->fetchColumn();

// Execute total sales query
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = $totalSalesResult->fetchColumn();

// Execute total visitors query
$totalVisitorsResult = $conn->query($totalVisitorsQuery);
$totalVisitors = $totalVisitorsResult->fetchColumn();

// Fetching sales data from the 'sold' table for the last 12 months
$monthlySalesQuery = "
    SELECT 
        DATE_FORMAT(order_date, '%M %Y') AS month, -- Full month name and year
        SUM(price * quantity) AS total_sales 
    FROM 
        sold 
    WHERE order_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY 
        month 
    ORDER BY 
        MIN(order_date)"; // Order by actual date, not string

$monthlySalesResult = $conn->query($monthlySalesQuery);
$months = [];
$salesData = [];

// Fetch data into arrays
while ($row = $monthlySalesResult->fetch(PDO::FETCH_ASSOC)) {
    $months[] = $row['month']; // Full month names for x-axis
    $salesData[] = (float)$row['total_sales']; // Total sales for each month
}


// Fetch sales data grouped by city
$citySalesQuery = "
    SELECT 
        city, 
        COUNT(*) AS total_sales 
    FROM 
        sold 
    GROUP BY 
        city";

$citySalesResult = $conn->query($citySalesQuery);
$cityLabels = [];
$cityData = [];

while ($row = $citySalesResult->fetch(PDO::FETCH_ASSOC)) {
    $cityLabels[] = $row['city'];
    $cityData[] = (int)$row['total_sales'];
}


// Fetch sales data grouped by year
$yearlySalesQuery = "
    SELECT 
        YEAR(order_date) AS year, 
        SUM(price * quantity) AS total_sales 
    FROM 
        sold 
    GROUP BY 
        year 
    ORDER BY 
        year ASC";

$yearlySalesResult = $conn->query($yearlySalesQuery);
$yearLabels = [];
$yearData = [];

while ($row = $yearlySalesResult->fetch(PDO::FETCH_ASSOC)) {
    $yearLabels[] = $row['year'];
    $yearData[] = (float)$row['total_sales'];
}


//for top products
$topProductsQuery = "
    SELECT 
       
        s.product_name AS product_name, 
        s.product_type AS type, 
        SUM(s.quantity) AS total_sales 
    FROM 
        products AS p 
    JOIN 
        sold AS s ON p.id = s.id 
    GROUP BY 
        p.id 
    ORDER BY 
        total_sales DESC 
    LIMIT 5"; 

$topProductsResult = $conn->query($topProductsQuery);
$topProducts = $topProductsResult->fetchAll(PDO::FETCH_ASSOC);


// Fetch total users
$userQuery = "SELECT COUNT(id) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch(PDO::FETCH_ASSOC)['total_users'];

// Fetch total visitors
$visitorQuery = "SELECT COUNT(id) AS total_visitors FROM visitors";
$visitorResult = $conn->query($visitorQuery);
$totalVisitors = $visitorResult->fetch(PDO::FETCH_ASSOC)['total_visitors'];


$query = "
    WITH total_sales AS (
        SELECT SUM(price * quantity) AS total_sales_value
        FROM sold
    ),
    top_products AS (
        SELECT 
            product_name,
            product_type,
            SUM(quantity) AS total_quantity_sold,
            SUM(price * quantity) AS total_sales,
            product_image
        FROM 
            sold
        GROUP BY 
            product_id, product_name, product_type, product_image
        ORDER BY 
            total_quantity_sold DESC
        LIMIT 5
    )
    SELECT 
        tp.product_name,
        tp.product_type,
        tp.total_quantity_sold,
        tp.total_sales,
        tp.product_image,
        (tp.total_sales / ts.total_sales_value * 100) AS sales_percentage
    FROM 
        top_products tp,
        total_sales ts;";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100">
    <?php include "../includes/header_admin.php"; ?>

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Sales Overview</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Orders Box -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Total Orders</h2>
                <p class="text-3xl font-bold"><?= $totalOrders ?></p>
            </div>

            <!-- Total Sales Box -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Total Sales</h2>
                <p class="text-3xl font-bold">₱<?= number_format($totalSales, 2) ?></p>
            </div>
            
            <!-- Total Visitors Box -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-2">Total Visitors</h2>
                <p class="text-3xl font-bold"><?= $totalVisitors ?></p>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-4" style="height: 410px;">
    <h2 class="text-xl font-semibold mb-2">Total Sales Over the Last 12 Months</h2>
    <canvas id="monthlySalesChart" style="height: 180px;"></canvas> <!-- Adjusted chart height -->
</div>

        <!-- <div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-xl font-semibold mb-2">Top Products</h2>
    <ul>
        <?php foreach ($topProducts as $product): ?>
            <li class="flex items-center mb-4">
                <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>" class="w-16 h-16 rounded mr-4">
                <div>
                    <h3 class="font-bold"><?= $product['product_name'] ?></h3>
                    <p class="text-sm">Type: <?= $product['product_type'] ?></p>
                    <p class="text-sm">Total Sold: <?= $product['total_sales'] ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div> -->


<div class="flex space-x-4 mt-6">
    <!-- Pie Chart for Sales Distribution by City -->
    <div class="bg-white shadow-md rounded-lg p-10 w-1/2" style="height: 380px;">
        <h2 class="text-xl font-semibold mb-2">Sales Distribution by City</h2>
        <canvas id="citySalesDistributionChart" style="height: 100px;"></canvas>
    </div>

    <!-- Bar Chart for Sales Per Year -->
    <div class="bg-white shadow-md rounded-lg p-4 w-1/2" style="height: 380px;">
        <h2 class="text-xl font-semibold mb-2">Sales Per Year</h2>
        <canvas id="yearlySalesChart" style="height: 100px;"></canvas>
    </div>

   
</div>
<br>
<div class="flex space-x-4 mt-6">
<div class="bg-white shadow-md rounded-md p-4 w-1/2" style="height: 380px; overflow: auto;">
    <h1 class="text-xl font-semibold mb-2">User and Visitor Statistics</h1>
    <canvas id="visitorChart" style="height: 80px;"></canvas> <!-- Adjusted height -->
    <canvas id="yearSalesChart" style="height: 80px;"></canvas> <!-- Example for another chart -->
</div>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4">Top 5 Selling Products</h2>
    
    <?php if ($result->rowCount() > 0): ?>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr> 
                   <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Image</th>
                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Product Name</th>
                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Type</th>
                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Total Sold</th>
                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Total Sales</th>
                    <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Sales Percentage</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr class="border-b hover:bg-gray-100">
                    <td class="py-4 px-4">
                            <img src="../uploads/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="w-16 h-16 object-cover">
                        </td>
                        <td class="py-4 px-4"><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td class="py-4 px-4"><?php echo htmlspecialchars($row['product_type']); ?></td>
                        <td class="py-4 px-4"><?php echo htmlspecialchars($row['total_quantity_sold']); ?></td>
                        <td class="py-4 px-4"><?php echo htmlspecialchars(number_format($row['total_sales'], 2)); ?></td>
                        <td class="py-4 px-4"><?php echo htmlspecialchars(number_format($row['sales_percentage'], 2)) . '%'; ?></td>
                       
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-gray-600">No sales data available.</p>
    <?php endif; ?>
</div>
</div>
<script>
    // Bar Chart for Yearly Sales
    const ctx4 = document.getElementById('yearlySalesChart').getContext('2d');
    const yearlySalesChart = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($yearLabels); ?>,
            datasets: [{
                label: 'Total Sales ($)',
                data: <?php echo json_encode($yearData); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Year'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales ($)'
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    // Pie Chart for City Distribution
    const ctx3 = document.getElementById('citySalesDistributionChart').getContext('2d');
    const citySalesDistributionChart = new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($cityLabels); ?>,
            datasets: [{
                label: 'Sales Distribution by City',
                data: <?php echo json_encode($cityData); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Legend position
                    labels: {
                        boxWidth: 15 // Width of the color box in the legend
                    }
                },
                title: {
                    display: true,
                    text: 'Sales Distribution by City'
                }
            }
        }
    });
</script><script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
<script>
    const ctx = document.getElementById('monthlySalesChart').getContext('2d');

    // Use PHP to output the arrays as JavaScript data
    const months = <?php echo json_encode($months); ?>;
    const salesData = <?php echo json_encode($salesData); ?>;

    // Initialize Chart.js to display the data
    const monthlySalesChart = new Chart(ctx, {
        type: 'bar', // Change chart type to 'bar'
        data: {
            labels: months, // Full month names on x-axis
            datasets: [{
                label: 'Total Sales (₱)', // Changed from '$' to '₱'
                data: salesData, // Sales data points
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light background fill
                borderColor: 'rgba(75, 192, 192, 1)', // Bar border color
                borderWidth: 2,
                hoverBackgroundColor: 'rgba(75, 192, 192, 0.4)', // Bar hover color
                fill: false // No fill for bars
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales (₱)' // Changed from '$' to '₱'
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1000 // Adjust based on sales data range
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let previous = context.rawIndex > 0 ? context.dataset.data[context.rawIndex - 1] : null;
                            let difference = previous ? context.raw - previous : 0;
                            let trend = difference > 0 ? '↑' : (difference < 0 ? '↓' : '');
                            return context.dataset.label + ': ₱' + context.raw.toLocaleString() + ' ' + trend; // Changed to peso symbol
                        }
                    }
                }
            }
        }
    });
</script>

<script>
        const ctx5 = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx5, {
            type: 'doughnut',
            data: {
                labels: ['Users', 'Visitors'],
                datasets: [{
                    label: 'Total Count',
                    data: [<?= $totalUsers ?>, <?= $totalVisitors ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true, 
                plugins: {
                    legend: {
                        position: 'top', 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: ${tooltipItem.raw}`; 
                            }
                        }
                    }
                }
            }
        });
    </script>
<?php
$conn = null; 
?>

