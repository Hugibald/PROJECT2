<?php
function calculate_final_price($price, $discount)
{
    return $discount > 0 ? $price * (1 - $discount / 100) : $price;
}

// Optional: Flash-Message Helper
function set_flash($message, $type = 'success')
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
