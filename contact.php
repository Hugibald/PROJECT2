<?php
session_start();
require_once "components/db_connect.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Contact Us</title>
</head>

<body>
  <?= $navbar ?>
        <div class="container my-5">
        <div class="content-box">
            <h1 class="text-center mb-4">Contact Us</h1>
            <p class="text-center text-muted">Get in touch with Beanternet</p>

            <h2 class="section-title">Our Address</h2>
            <p class="contact-info">
                <i class="bi bi-geo-alt-fill"></i>
                Beispielstraße 12
                <br>
                <i class="bi bi-geo-alt-fill transparent"></i> 1010 Wien
                <br>
                <i class="bi bi-geo-alt-fill transparent"></i> Österreich
            </p>

            <h2 class="section-title">Email</h2>
            <p class="contact-info">
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:beanternet@support.org">beanternet@support.org</a>
            </p>

            <h2 class="section-title">Phone</h2>
            <p class="contact-info">
                <i class="bi bi-telephone-fill"></i>
                +43 660 1234567 (dummy number for project)
            </p>

            <h2 class="section-title">Find Us on Google Maps</h2>
            <div class="ratio ratio-16x9">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2674.768091959368!2d16.366781415814205!3d48.210033179227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x476d0798f894a1f3%3A0x19b03439b3f5fbc2!2s1010%20Wien%2C%20Austria!5e0!3m2!1sen!2sus!4v1702330100000!5m2!1sen!2sus"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <?= $footer ?>
</body>

</html>
