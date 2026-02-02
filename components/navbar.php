<?php
$navbar = '
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand text-center" href="index.php">
        <img src="pictures/LOGO.svg" alt="Logo" width="30" height="30">
        <div class="brand-text fs-6">beanternet</div>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
';

// ================================
// GUEST NAVBAR
// ================================
if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
  $navbar .= '
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="products.php">Coffee</a></li>
        <li class="nav-item"><a class="nav-link active" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
    ';
}

// ================================
// ADMIN NAVBAR
// ================================
elseif (isset($_SESSION["admin"])) {
  $navbar .= '
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="users/user-list.php">User</a></li>
        <li class="nav-item"><a class="nav-link active" href="products/dashboard.php">Product</a></li>
        <li class="nav-item"><a class="nav-link active" href="order_management/orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link active" href="suppliers/dashboard.php">Supplier</a></li>
        <li class="nav-item"><a class="nav-link active" href="index.php">Index</a></li>
        <li class="nav-item"><a class="nav-link active" href="logout.php?logout">Logout</a></li>
    ';
}

// ================================
// LOGGED-IN USER NAVBAR
// ================================
else {
  $navbar .= '
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="userprofile.php">Products</a></li>
        <li class="nav-item"><a class="nav-link active" href="update_profile.php?id=' . $_SESSION["user"] . '">Update Profile</a></li>
        <li class="nav-item"><a class="nav-link active" href="cart/cart.php">Cart</a></li>
        <li class="nav-item"><a class="nav-link active" href="orders.php">My Orders</a></li>
        <li class="nav-item"><a class="nav-link active" href="logout.php?logout">Logout</a></li>
    ';
}

$navbar .= '
      </ul>
    </div>
  </div>
</nav>';
