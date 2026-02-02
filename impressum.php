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
    <title>Impressum</title>
</head>

<body>
    <?= $navbar ?>
      <div class="container my-5">
        <div class="content-box">
            <h1 class="text-center mb-4">Impressum</h1>
            <p class="text-center text-muted">Beanternet</p>

            <h4 class="section-title">1. Company Information</h4>
            <p>
                <strong>Beanternet – Einzelunternehmen</strong><br>
                Inhaber: Max Beispielmann<br>
                Beispielstraße 12<br>
                1010 Wien<br>
                Österreich
            </p>

            <h4 class="section-title">2. Contact Information</h4>
            <p>
                E-Mail: <a href="mailto:beanternet@support.org">beanternet@support.org</a><br>
                <!-- Telefon: nicht vorhanden  -->
            </p>

            <h4 class="section-title">3. Business Purpose</h4>
            <p>
                Direktimport, Röstung, Verpackung und Verkauf von Kaffeebohnen von Kleinbauern aus verschiedenen Ländern.
            </p>

            <h4 class="section-title">4. Legal Information</h4>
            <p>
                Rechtsform: Einzelunternehmen <br>
                Mitglied der Wirtschaftskammerorganisation: WKO Wien<br>
                Aufsichtsbehörde: Magistratisches Bezirksamt Wien<br>
                <!-- Umsatzsteuer-Identifikationsnummer (UID): nicht vergeben (Projektarbeit)<br> -->
                <!-- Gewerbebehörde: Fiktiv im Rahmen des Ausbildungsprojekts -->
            </p>
<!--
            <h4 class="section-title">5. Disclaimer</h4>
            <p>
                Diese Website und die dargestellten Inhalte dienen ausschließlich Ausbildungszwecken im Rahmen einer
                Full-Stack-Web-Development-Ausbildung. Es handelt sich nicht um ein tatsächlich betriebenes Unternehmen
                und es findet kein realer Warenverkehr statt.
            </p> -->
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
