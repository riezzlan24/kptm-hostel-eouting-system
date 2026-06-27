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

$sql = "
SELECT *
FROM sos_alert
ORDER BY id DESC
";

$result = mysqli_query($conn,$sql);

?>

<h2 class="fw-bold mt-3">

SOS Alerts

</h2>

<p class="text-muted">

Monitor emergency requests submitted by students.

</p>

<hr>

<div class="card">

<div class="card-body">

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>ID</th>

<th>Student ID</th>

<th>Emergency Message</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>

<td>

#<?php echo $row['id']; ?>

</td>

<td>

<?php echo $row['student_id']; ?>

</td>

<td>

<?php echo $row['message']; ?>

</td>

<td>

<?php

if($row['status']=="Open")
{
?>

<span class="badge badge-open px-3 py-2">

Open

</span>

<?php
}
else
{
?>

<span class="badge badge-closed px-3 py-2">

Closed

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