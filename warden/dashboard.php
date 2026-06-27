<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warden')
{
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';



$pending =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM outing_request
WHERE status='Pending'"
)
)['total'];



$outside =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM outing_request
WHERE current_status='Outside'"
)
)['total'];



$sos =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM sos_alert
WHERE status='Open'"
)
)['total'];



$history =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total
FROM outing_request
WHERE status='Completed'"
)
)['total'];

?>

<h2
class="fw-bold"
style="color:#1D1D1F;">

Welcome back,

<?php echo $_SESSION['fullname']; ?>

👋

</h2>

<p class="text-muted">

Monitor and manage hostel activities.

</p>

<br>

<div class="row g-4">

<div class="col-md-3">

<a
href="pending_request.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center p-4">

<h1>

<?php echo $pending; ?>

</h1>

<p class="text-muted mb-0">

Pending Requests

</p>

</div>

</div>

</a>

</div>



<div class="col-md-3">

<a
href="active_students.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center p-4">

<h1>

<?php echo $outside; ?>

</h1>

<p class="text-muted mb-0">

Students Outside

</p>

</div>

</div>

</a>

</div>



<div class="col-md-3">

<a
href="sos_alert.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center p-4">

<h1>

<?php echo $sos; ?>

</h1>

<p class="text-muted mb-0">

Open SOS Alerts

</p>

</div>

</div>

</a>

</div>



<div class="col-md-3">

<a
href="history.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center p-4">

<h1>

<?php echo $history; ?>

</h1>

<p class="text-muted mb-0">

Completed Outings

</p>

</div>

</div>

</a>

</div>

</div>

<?php

include '../includes/footer.php';

?>