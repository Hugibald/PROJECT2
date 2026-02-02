<?php

/**
 * Sends order confirmation email to the customer.
 */

function send_mail($user, $items, $order_id, $total)
{
    if (!isset($user) || !isset($order_id) || !isset($items) || !isset($total)) {
        error_log("send_mail.php: missing data");
        return;
    }

    $user_email = $user['email'];
    $user_name  = htmlspecialchars($user['first_name']);
    $subject    = "Your order #$order_id at Beanternet";

    // E-Mail Body
    $message = "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<title>Order Confirmation</title>
</head>
<body>
<h2>Thank you for your order, $user_name!</h2>
<p>Your order number: <strong>$order_id</strong></p>
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
<p>Your order will be processed as soon as possible.</p>
<p>Thank you for shopping with us!</p>
</body>
</html>";

    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Beanternet <shop@beanternet.com>" . "\r\n";

    if (!mail($user_email, $subject, $message, $headers)) {
        error_log("send_mail.php: Mail could not be sent to: $user_email");
    }
}
