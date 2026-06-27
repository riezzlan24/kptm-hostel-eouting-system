<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guard') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

?>

<h1 class="mt-3">

Guard Dashboard

</h1>

<p>

Welcome,

<b>

<?php echo $_SESSION['fullname']; ?>

</b>

</p>

<hr>

<div class="row">

<div class="col-md-4">

<div class="card">

<div class="card-body">

<h5 class="card-title">

QR Scanner

</h5>

<p class="card-text">

Scan student QR codes for check-out and check-in.

</p>

<a href="scanner.php"
class="btn btn-primary">

Open Scanner

</a>

</div>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>