<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin')
{
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

?>

<h2 class="fw-bold mt-3">

Student Monitoring

</h2>

<p class="text-muted">

Monitor active outings and emergency cases.

</p>

<hr>

<?php

$sql = "
SELECT *
FROM outing_request
WHERE current_status='Outside'
";

$result = mysqli_query($conn,$sql);

?>

<div class="card mb-4">

<div class="card-body">

<h4 class="mb-4">

Active Outing Students

</h4>

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Student ID</th>

<th>Destination</th>

<th>Check-Out Time</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= $row['student_id'] ?>

</td>

<td>

<?= $row['destination'] ?>

</td>

<td>

<?= $row['checkout_time'] ?>

</td>

<td>

<span class="badge badge-outside px-3 py-2">

Outside

</span>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>



<?php

$sql2 = "
SELECT *
FROM sos_alert
WHERE status='Open'
";

$result2 = mysqli_query($conn,$sql2);

?>

<div class="card">

<div class="card-body">

<h4 class="mb-4">

SOS Cases

</h4>

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Student ID</th>

<th>Message</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result2)) { ?>

<tr>

<td>

#<?= $row['id'] ?>

</td>

<td>

<?= $row['student_id'] ?>

</td>

<td>

<?= $row['message'] ?>

</td>

<td>

<span class="badge badge-open px-3 py-2">

<?= $row['status'] ?>

</span>

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