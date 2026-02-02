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
    <title>Terms & Conditions</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container my-5">
        <div class="content-box">
            <h1>Terms & Conditions</h1>

            <p><strong>Last updated:</strong> 11.12.2025</p>

            <h4 class="section-title">1. Introduction</h4>
            <p>
                Welcome to <strong>Beanternet</strong>, operated by Beanternet GmbH (“we”, “us”, “our”).
                These Terms & Conditions govern all purchases and interactions on our website.
                By using our services, you agree to these terms.
            </p>

            <h4 class="section-title">2. Eligibility & Business Customers</h4>
            <p>
                We provide products to both Consumers (B2C) and Business Clients (B2B).
                Business customers may be required to provide a valid VAT/UID number.
                Additional commercial terms may apply.
            </p>

            <h4 class="section-title">3. Products & Availability</h4>
            <p>
                All products, descriptions, and prices listed on our website are subject to change.
                Product images are for illustration and may differ from actual items.
            </p>

            <h4 class="section-title">4. Pricing & Payment</h4>
            <p>
                Prices include VAT for consumers and exclude VAT for eligible business customers.
                Payments are accepted through the available checkout methods.
            </p>

            <h4 class="section-title">5. Orders & Contract Formation</h4>
            <p>
                Submitting an order constitutes a binding purchase offer.
                A contract is formed once we send an order confirmation via email.
            </p>

            <h4 class="section-title">6. Shipping & Delivery</h4>
            <p>
                Delivery times and shipping fees are shown during checkout.
                We are not responsible for delays caused by carriers or external circumstances.
            </p>

            <h4 class="section-title">7. Returns & Refunds</h4>
            <p>
                Consumers may have withdrawal rights according to law.
                Opened food products (such as coffee) cannot be returned.
                Business orders generally cannot be returned unless defective.
            </p>

            <h4 class="section-title">8. Defects & Warranty</h4>
            <p>
                Defective products must be reported promptly.
                Remedies may include replacement, repair, or refund.
            </p>

            <h4 class="section-title">9. Customer Accounts</h4>
            <p>
                Customers are responsible for account security. We may suspend accounts in case of misuse.
            </p>

            <h4 class="section-title">10. Intellectual Property</h4>
            <p>
                All website content is protected by intellectual property laws and may not be reused without permission.
            </p>

            <h4 class="section-title">11. Data Protection & Privacy</h4>
            <p>
                Personal data is handled according to our Privacy Policy.
            </p>

            <h4 class="section-title">12. Limitation of Liability</h4>
            <p>
                We are not liable for indirect damages, business losses (B2B), or delays caused by third-party carriers.
            </p>

            <h4 class="section-title">13. Governing Law & Jurisdiction</h4>
            <p>
                These terms are governed by the laws of Austria.
                The exclusive place of jurisdiction is Vienna unless consumer laws state otherwise.
            </p>

            <!-- <h4 class="section-title">14. Contact Information</h4>
            <p>
                <strong>Beanternet GmbH</strong><br>
                Address: {{Company Address}}<br>
                Email: {{Support Email}}<br>
                Phone: {{Optional}}
            </p>
        </div> -->
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
