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
    <title>Privacy Policy</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container my-5">
        <div class="content-box">
            <h1>Privacy Policy</h1>
            <p>Information about how Beanternet collects and protects your data</p>

            <p><strong>Last updated:</strong> 11.12.2025</p>

            <h4 class="section-title">1. Introduction</h4>
            <p>
                This Privacy Policy explains how Beanternet GmbH (“we”, “us”, “our”) collects, processes, and protects your personal
                data when you use our online store, create an account, upload a profile picture, post product reviews, ask product questions,
                or place orders. We comply with the GDPR and all applicable data protection laws.
            </p>

            <h4 class="section-title">2. Data We Collect</h4>

            <h5>2.1 Account Information</h5>
            <p>
                When creating an account, we collect:
            <ul>
                <li>First and Last Name</li>
                <li>Email address</li>
                <li>Password (securely hashed and never stored in plain text)</li>
                <li>Profile picture (optional)</li>
            </ul>
            </p>

            <h5>2.2 Order & Delivery Information</h5>
            <p>
                To fulfill your orders, we collect:
            <ul>
                <li>Full name</li>
                <li>Billing & shipping address (same address field)</li>
            </ul>
            We do not collect a phone number.
            </p>

            <h5>2.3 User-Generated Content</h5>
            <p>
                If you interact with our community features, we collect:
            <ul>
                <li>Product reviews</li>
                <li>Questions about products</li>
                <li>Uploaded profile images</li>
            </ul>
            </p>

            <h5>2.4 Payment Information</h5>
            <p>
            <ul>
                <!-- <li><strong>External payment providers</strong> (e.g., PayPal, depending on integration).
                        We do not store full payment card details. We only receive confirmation of payment status.
                    </li> -->
                <li><strong>Invoice via email (bank transfer / prepayment)</strong>.
                    We will send you an email with the payment details.
                    The order is shipped only after payment is received.</li>
            </ul>
            </p>

            <h5>2.5 Technical & Usage Data</h5>
            <p>
                We collect:
            <ul>
                <li>IP address</li>
                <li>Browser and device information</li>
                <li>Cookies for session handling, login, and analytics</li>
                <li>Security and error logs</li>
            </ul>
            </p>

            <h4 class="section-title">3. How We Use Your Data</h4>
            <p>
                We use your data to:
            <ul>
                <li>Process and deliver your orders</li>
                <li>Manage your user account and login sessions</li>
                <li>Allow you to publish reviews, questions, and upload profile images</li>
                <li>Provide customer support</li>
                <li>Improve our website and services</li>
                <li>Fulfill legal obligations (e.g., tax and accounting)</li>
            </ul>
            </p>

            <h4 class="section-title">4. Legal Bases (GDPR)</h4>
            <p>
                We process data based on:
            <ul>
                <li><strong>Art. 6(1)(b)</strong> – contract fulfilment (orders, accounts)</li>
                <li><strong>Art. 6(1)(c)</strong> – legal obligations (tax laws)</li>
                <li><strong>Art. 6(1)(f)</strong> – legitimate interests (security, analytics)</li>
                <li><strong>Art. 6(1)(a)</strong> – consent (profile images, optional cookies)</li>
            </ul>
            </p>

            <h4 class="section-title">5. Cookies</h4>
            <p>
                We use cookies for:
            <ul>
                <li>Account login sessions</li>
                <li>Shopping cart functionality</li>
                <li>Basic website analytics</li>
            </ul>
            You may manage your cookie preferences in your browser.
            </p>

            <h4 class="section-title">6. Data Sharing</h4>
            <p>
                We share data only with service providers necessary to operate the store:
            <ul>
                <li>Shipping companies</li>
                <li>Payment providers (if chosen)</li>
                <li>Email service providers</li>
                <li>Hosting and IT infrastructure providers</li>
            </ul>
            We never sell personal data.
            </p>

            <h4 class="section-title">7. Public Content (Reviews & Questions)</h4>
            <p>
                When submitting reviews or product questions:
            <ul>
                <li>Your display name will appear publicly</li>
                <li>Your profile picture may appear publicly if you uploaded one</li>
            </ul>
            You may delete your content at any time.
            </p>

            <h4 class="section-title">8. Data Retention</h4>
            <p>
                We retain your data:
            <ul>
                <li>As long as your account remains active</li>
                <li>As required by law (e.g., tax retention periods)</li>
                <li>Or until you request deletion when allowed by law</li>
            </ul>
            </p>

            <h4 class="section-title">9. Security Measures</h4>
            <p>
                We protect your data with:
            <ul>
                <li>Encrypted connections (TLS)</li>
                <li>Hashed passwords</li>
                <li>Restricted server access</li>
                <li>Security monitoring</li>
            </ul>
            </p>

            <h4 class="section-title">10. Your GDPR Rights</h4>
            <p>
                You have the right to:
            <ul>
                <li>Access your data</li>
                <li>Correct incorrect data</li>
                <li>Delete your data ("right to be forgotten")</li>
                <li>Withdraw consent</li>
                <li>Restrict processing</li>
                <li>Receive a copy of your data (data portability)</li>
                <li>File a complaint with a data protection authority</li>
            </ul>
            Contact us at: <strong>beanternet@support.org</strong>
            </p>

            <h4 class="section-title">11. Account Deletion</h4>
            <p>
                You may request deletion of your account at any time.
                We will delete or anonymize all personal data unless legally required to retain certain records.
            </p>

            <h4 class="section-title">12. International Data Transfers</h4>
            <p>
                If personal data is transferred outside the EU/EEA, we ensure appropriate safeguards such as Standard Contractual Clauses.
            </p>

            <h4 class="section-title">13. Contact Information</h4>
            <p>
                <strong>Beanternet GmbH</strong><br>
                Address: {{Company Address}}<br>
                Email: beanternet@support.org<br>
                Phone: (not collected)
            </p>
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
