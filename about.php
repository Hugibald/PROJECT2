<?php
session_start();
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

$sql = "select * from supplier";
$result = mysqli_query($connect, $sql); // Go button

$layout = "";

if (mysqli_num_rows($result) > 0) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $row) {
        $layout .= "
        <div>
            <div class='card my-3'>
                <img src='pictures/supplier/{$row["supplier_picture"]}' class='card-img-top' alt='{$row["name"]}'>
                <div class='card-body'>
                    <h5 class='card-title text-success'>{$row["name"]}</h5>
                    <p class='card-text'><strong>{$row["location"]}</strong></p>
                    <a href='farmers.php?id={$row["supplier_id"]}' class='btn btn-success my-2'>Learn more</a>
                </div>
            </div>
        </div>
    ";
    }
} else {
    $layout = "<h3>No Data found</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>About Beanternet</title>
</head>

<body>
    <?= $navbar ?>
    <!-- <div class="container">
        <h5 class="text-success text-center my-3">About Us </h5>
        <p class="text-center"> Welcome to Beanternet – your destination for premium coffee beans from around the world!
            We source our beans directly from small farmers in Africa, Asia, and South America, ensuring the highest quality while supporting the people who grow them. Every bean carries the story of sustainable farming, dedication, and tradition.
            Originally, we started as a local shop in Vienna, sharing fresh coffee experiences with our community. <br><br> Now, with our online store, we bring those same flavors and stories to your doorstep, wherever you are.
            At Beanternet, we are green, sustainable, and committed to empowering small farmers, because great coffee should make a positive impact on people and the planet.
            <br> Beanternet – Sip Beyond Borders.
        </p>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
            <?= $layout ?>
        </div>
    </div> -->
    <div class="container my-5">
        <div class="content-box">
            <h1>About Us</h1>
            <p class="text-muted">Discover our story, our coffee, and the farmers behind it</p>

            <h2 class="section-title icon-title"><i class="bi bi-person-circle"></i>Who We Are</h2>
            <img src="pictures/about/Saplings.png" class="img-fluid my-3" alt="Coffee Saplings">
            <div class="clearfix"></div>
            <img src="pictures/about/Flowers.png" class="img-fluid float-start coffee-left" alt="Coffee Flowers">
            <p>
                Beanternet is a small, independent coffee shop based in Vienna. We specialize in sourcing
                <strong>high-quality coffee beans directly from carefully selected farmers around the world</strong>.
                Our mission is to create <strong>sustainable value for both the farmers and our customers</strong>,
                connecting people through exceptional coffee.
            </p>
            <img src="pictures/about/Growing.png" class="img-fluid float-end coffee-right" alt="Berry-Buds">
            <h2 class="section-title icon-title"><i class="bi bi-tree-fill"></i>Our Philosophy</h2>
            <p>
                At Beanternet, we care deeply about:
            </p>
            <img src="pictures/about/Riping.png" class="img-fluid float-start coffee-left" alt="Riping Berries">
            <ul>
                <li><strong>Sustainability:</strong> Supporting farming practices that preserve biodiversity and the local ecosystem.</li>
                <li><strong>Fair Compensation:</strong> Farmers are paid according to <strong>FairTrade principles</strong>.</li>
                <li><strong>Community & Heritage:</strong> Helping preserve traditional farming methods and unique coffee varieties.</li>
            </ul>
            <div class="clearfix"></div>
            <img src="pictures/about/Ripe.png" class="img-fluid float-end coffee-right" alt="Ripe Berries">

            <h4 class="section-title icon-title"><i class="bi bi-truck-front-fill"></i>From Farm to Cup</h4>

            <p>
                Every step of the coffee journey is designed with care:
            </p>
            <ol>
                <li><strong>Direct Import:</strong> Sourcing beans directly from partner farms to reduce intermediaries.</li>
                <li><strong>Fresh Roasting:</strong> All beans are roasted <strong>on-site in Vienna</strong> for optimal freshness.</li>
                <li><strong>Eco-Friendly Packaging:</strong> Local packaging minimizes transport and environmental impact.</li>
            </ol>
            <div class="clearfix"></div>
            <h2 class="section-title icon-title"><i class="bi bi-globe2"></i>Online & Local</h2>
            <img src="pictures/about/Harvest.png" class="img-fluid float-start coffee-left" alt="Berry Harvest">
            <p>
                While we love serving coffee in our Vienna shop, our <strong>online store</strong> allows coffee lovers everywhere
                to enjoy our ethically sourced and sustainable coffee blends.
            </p>
            <img src="pictures/about/Beans.png" class="img-fluid float-end coffee-right" alt="Fresh Beans">
            <div class="clearfix"></div>
            <h4 class="section-title icon-title"><i class="bi bi-people-fill"></i>Meet Our Farmers</h4>
            <img src="pictures/about/Cleaning.png" class="img-fluid float-start coffee-left" alt="Berry Cleaning">
            <p>
                We believe in <strong>transparency and connection</strong>. On our Farmers page, you can learn about the people
                behind your coffee – where it’s grown, how it’s cultivated, and the stories of the communities we support.
                Every purchase strengthens these communities and preserves their way of life.
            </p>
            <div class="clearfix"></div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
                <?= $layout ?>
            </div>

            <h2 class="section-title icon-title"><i class="bi bi-heart-fill"></i>Why Choose Beanternet?</h2>
            <img src="pictures/about/Drying.png" class="img-fluid float-end coffee-right" alt="Drying Beans">
            <ul>
                <li>Sustainably sourced beans from around the world</li>
                <li>Fair payment to farmers</li>
                <li>Local roasting & packaging in Vienna</li>
                <li>Direct connection between consumer and origin</li>
                <li>High-quality, fresh coffee blends available online and in-store</li>
            </ul>
            <img src="pictures/about/Roasting.png" class="img-fluid float-start coffee-left mb-3" alt="Roasting Beans">
            <p class="mt-4"><strong>Our mission is simple:</strong> To deliver coffee that is <strong>delicious, ethical, and sustainable</strong>,
                from our partner farmers to your cup.</p>

        </div>
    </div>

    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
