<?php
require_once "components/restriction_admin.php";
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

// ============================
// Admin Info
// ============================
$sqlAdmin = "SELECT * FROM users WHERE user_id = {$_SESSION['admin']}";
$resultAdmin = mysqli_query($connect, $sqlAdmin);
$admin = mysqli_fetch_assoc($resultAdmin);

// ============================
// Statistics Time
// ============================
$timeFilter = $_GET['time_filter'] ?? 'all';
$whereDate = "";
switch ($timeFilter) {
    case 'week':
        $whereDate = "WHERE oi.order_id IN (
            SELECT order_id FROM orders
            WHERE order_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        )";
        break;
    case 'month':
        $whereDate = "WHERE oi.order_id IN (
            SELECT order_id FROM orders
            WHERE order_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
        )";
        break;
    default:
        $whereDate = ""; // all time
}

// ============================
// Product Sales Statistics
// ============================
$sqlStats = "
SELECT
    p.product_id,
    p.name,
    p.product_picture,
    SUM(oi.quantity) AS total_sold
FROM products p
LEFT JOIN order_items oi ON p.product_id = oi.product_id
" . ($whereDate ? $whereDate : "") . "
GROUP BY p.product_id, p.name, p.product_picture
HAVING total_sold > 0
ORDER BY total_sold DESC
";

$resultStats = mysqli_query($connect, $sqlStats);

// Arrays needed for Chart.js
$productNames = [];
$productSales = [];
$salesLayout = "";

if (mysqli_num_rows($resultStats) > 0) {
    while ($rowStat = mysqli_fetch_assoc($resultStats)) {
        $productNames[] = $rowStat['name'];
        $productSales[] = (int)$rowStat['total_sold'];

        $salesLayout .= "
        <div class='card my-2' style='width: 18rem; display:inline-block; margin-right:10px;'>
            <img src='/pictures/products/{$rowStat['product_picture']}' class='card-img-top' alt='{$rowStat['name']}'>
            <div class='card-body'>
                <h5 class='card-title'>{$rowStat['name']}</h5>
                <p class='card-text'>Sold: {$rowStat['total_sold']} units</p>
            </div>
        </div>
        ";
    }
} else {
    $salesLayout = "<p>No sales yet.</p>";
}

// ============================
// Order management
// ============================

$sqlOrders = "SELECT o.order_id, o.order_date, o.order_status, o.total_cost, u.first_name, u.last_name
FROM orders o
JOIN users u ON o.user_id = u.user_id
WHERE order_status IN ('pending', 'shipped', 'paid')";
$resultOrders = mysqli_query($connect, $sqlOrders);

$layoutOrders = "";
if (mysqli_num_rows($resultOrders) > 0) {
    $rowsOrders = mysqli_fetch_all($resultOrders, MYSQLI_ASSOC);
    foreach ($rowsOrders as $rowO) {
        $layoutOrders .= "
        <tbody>
            <tr>
                <td>{$rowO["first_name"]} {$rowO["last_name"]}</td>
                <td>{$rowO["order_date"]}</td>
                <td>{$rowO["total_cost"]}</td>
                <td>{$rowO["order_status"]}</td>
                <td>
                <a href='order_management/orders.php?id={$rowO['order_id']}' class='btn btn-sm btn-success'>View</a>
                </td>
            </tr>
    </tbody>
    ";
    }
} else {
    $layoutOrders = "<h5 class='text text-danger'>No open Orders</h5>";
}


// ============================
// Product reviews management
// ============================

$sqlReviews = "SELECT i.product_id, r.review_id, r.rating, r.rating_text, review_date
FROM reviews r
JOIN order_items i ON r.order_item_id = i.order_item_id
WHERE review_ok = 0";
$resultReviews = mysqli_query($connect, $sqlReviews);

$layoutReviews = "";
if (mysqli_num_rows($resultReviews) > 0) {
    $rowsReviews = mysqli_fetch_all($resultReviews, MYSQLI_ASSOC);
    foreach ($rowsReviews as $rowR) {
        $layoutReviews .= "
        <tbody>
            <tr>
                <td>{$rowR["review_date"]}</td>
                <td>{$rowR["rating"]}</td>
                <td>{$rowR["rating_text"]}</td>
                <td>
                <a href='products/details.php?id={$rowR['product_id']}#review-{$rowR['review_id']}' class='btn btn-sm btn-success'>View</a>
                </td>
            </tr>
    </tbody>
    ";
    }
} else {
    $layoutReviews = "<h5 class='text text-danger'>No open Reviews</h5>";
}

// ============================
// User questions management
// ============================
$sqlQuestions = "SELECT * FROM questions WHERE answer IN ('', NULL)";
$resultQuesions = mysqli_query($connect, $sqlQuestions);

$layoutQuestions = "";
if (mysqli_num_rows($resultQuesions) > 0) {
    $rowsQuesions = mysqli_fetch_all($resultQuesions, MYSQLI_ASSOC);
    foreach ($rowsQuesions as $rowQ) {
        $layoutQuestions .= "
        <tbody>
            <tr>
                <td>{$rowQ["question_date"]}</td>
                <td>{$rowQ["question"]}</td>
                <td>
                    <a href='products/details.php?id={$rowQ['product_id']}#question-{$rowQ['question_id']}' class='btn btn-sm btn-success'>View</a>
                </td>
            </tr>
    </tbody>
    ";
    }
} else {
    $layoutQuestions = "<h5 class='text text-danger'>No open Questions</h5>";
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?= $links ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?= $navbar ?>

    <div class="container mt-3">
        <!-- Admin Info -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <img src="pictures/user/<?= $admin['user_picture'] ?>" alt="<?= $admin['first_name'] ?>" style="width:75px;">
            <h4>Welcome <?= $admin['first_name'] ?> <?= $admin['last_name'] ?></h4>
        </div>
        <h1>Admin Dashboard</h1>
        <!-- Time Filter -->
        <h2>Sales Overview</h2>
        <div class="d-flex mb-3 justify-content-between align-items-center">
            <form method="get" class="d-flex gap-2 align-items-center">
                <label for="time_filter">Filter by time:</label>
                <select name="time_filter" id="time_filter" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="all" <?= $timeFilter === 'all' ? 'selected' : '' ?>>All time</option>
                    <option value="week" <?= $timeFilter === 'week' ? 'selected' : '' ?>>Last week</option>
                    <option value="month" <?= $timeFilter === 'month' ? 'selected' : '' ?>>Last month</option>
                </select>
            </form>

        </div>


        <!-- Chart -->
        <canvas id="salesChart" height="100"></canvas>

        <!-- Product Sales Statistics -->
        <h2 class="mt-5">Product Sales Statistics</h2>
        <div class="d-flex flex-wrap mb-4">
            <?= $salesLayout ?>
        </div>

        <!-- Orders -->
        <h2>Orders</h2>
        <div class="d-flex flex-wrap mb-4">
            <table class="table table-striped my-4">
                <thead>
                    <tr>
                        <th class="px-1">user</th>
                        <th class="px-1">order_date</th>
                        <th class="px-1">total_cost</th>
                        <th class="px-1">order_status</th>
                        <th class="px-1"></th>
                    </tr>
                </thead>
                <?= $layoutOrders ?>
            </table>
        </div>

        <!-- User Reviews -->
        <h2>User Reviews</h2>
        <div class="d-flex flex-wrap mb-4">
            <table class="table table-striped my-4">
                <thead>
                    <tr>
                        <th class="px-1">review_date</th>
                        <th class="px-1">rating</th>
                        <th class="px-1">review_text</th>
                        <th class="px-1"></th>
                    </tr>
                </thead>
                <?= $layoutReviews ?>
            </table>
        </div>

        <!-- User Questions -->
        <h2>User Questions</h2>
        <div class="d-flex flex-wrap mb-4">
            <table class="table table-striped my-4">
                <thead>
                    <tr>
                        <th class="px-1">question_date</th>
                        <th class="px-1">question</th>
                        <th class="px-1"></th>
                    </tr>
                </thead>
                <?= $layoutQuestions ?>
            </table>
        </div>
    </div>
    <!-- Footer -->
    <?= $footer ?>

        <script>
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($productNames) ?>,
                    datasets: [{
                        label: 'Units Sold',
                        data: <?= json_encode($productSales) ?>,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Product Sales Overview'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Units Sold'
                            }
                        }
                    }
                }
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
