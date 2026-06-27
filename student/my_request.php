<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student')
{
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$student_id = $_SESSION['user_id'];

$sql = "
SELECT *
FROM outing_request
WHERE student_id='$student_id'
ORDER BY id DESC
";

$result = mysqli_query($conn,$sql);

?>

<h2 class="fw-bold mt-3">

My Requests

</h2>

<p class="text-muted">

View and track your outing applications.

</p>

<hr>

<div class="card">

<div class="card-body">

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Date</th>

<th>Time</th>

<th>Destination</th>

<th>Status</th>

<th>Current Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>

<td>

#<?php echo $row['id']; ?>

</td>

<td>

<?php echo $row['outing_date']; ?>

</td>

<td>

<?php echo $row['outing_time']; ?>

</td>

<td>

<?php echo $row['destination']; ?>

</td>

<td>

<?php

if($row['status']=="Pending")
{
?>

<span class="badge rounded-pill text-dark bg-warning px-3 py-2">

Pending

</span>

<?php
}
elseif($row['status']=="Approved")
{
?>

<span class="badge rounded-pill bg-success px-3 py-2">

Approved

</span>

<?php
}
elseif($row['status']=="Rejected")
{
?>

<span class="badge rounded-pill bg-danger px-3 py-2">

Rejected

</span>

<?php
}
else
{
?>

<span class="badge rounded-pill bg-primary px-3 py-2">

Completed

</span>

<?php
}

?>

</td>

<td>

<?php

if($row['current_status']=="Outside")
{
?>

<span class="badge rounded-pill bg-info px-3 py-2">

Outside

</span>

<?php
}
else
{
?>

<span class="badge rounded-pill bg-secondary px-3 py-2">

Inside

</span>

<?php
}

?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>