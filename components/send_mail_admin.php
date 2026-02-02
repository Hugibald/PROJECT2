<?php

/**
 * send_mail_admin.php
 * Sends notification email to the admin about a new order.
 */

function send_mail_admin($user, $items, $order_id, $total)
{
    if (!isset($user) || !isset($order_id) || !isset($items) || !isset($total)) {
        error_log("send_mail_admin.php: missing data");
        return;
    }

    $admin_email = "admin@example.com";
    $subject = "New Order #$order_id arrived";

    // E-Mail Body
    $message = "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>New Order Notification</title>
</head>
<body>
<h2>New order by {$user['first_name']} ({$user['email']})</h2>
<p>Order Number: <strong>$order_id</strong></p>
<table border='1' cellpadding='5' cellspacing='0'>
<tr>
<th>Product</th>
<th>Quantity</th>
<th>Price</th>
<th>Subtotal</th>
</tr>";

    foreach ($items as $item) {
        $name = htmlspecialchars($item['name']);
        $qty  = (int)$item['quantity'];
        $price = number_format($item['final_price'], 2);
        $subtotal = number_format($item['final_price'] * $qty, 2);

        $message .= "<tr>
<td>$name</td>
<td>$qty</td>
<td>$price €</td>
<td>$subtotal €</td>
</tr>";
    }

    $message .= "</table>
<p><strong>Total:</strong> " . number_format($total, 2) . " €</p>
</body>
</html>";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Beanternet <shop@beanternet.com" . "\r\n";

    if (!mail($admin_email, $subject, $message, $headers)) {
        error_log("send_mail_admin.php: Mail could not be sent to: $admin_email");
    }
}
