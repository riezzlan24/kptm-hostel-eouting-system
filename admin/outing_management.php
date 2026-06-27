<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$sql = "SELECT *
        FROM outing_request
        ORDER BY id DESC";

$result = mysqli_query($conn,$sql);

?>

<h1 class="mt-3">

Outing Management

</h1>

<hr>

<div class="card">

<div class="card-body">

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>

<th>Student ID</th>

<th>Date</th>

<th>Destination</th>

<th>Status</th>

<th>Current Status</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>

<td>

<?= $row['id'] ?>

</td>

<td>

<?= $row['student_id'] ?>

</td>

<td>

<?= $row['outing_date'] ?>

</td>

<td>

<?= $row['destination'] ?>

</td>

<td>

<?php

if($row['status']=="Pending")
{
    echo "<span class='badge bg-warning'>Pending</span>";
}
elseif($row['status']=="Approved")
{
    echo "<span class='badge bg-success'>Approved</span>";
}
elseif($row['status']=="Rejected")
{
    echo "<span class='badge bg-danger'>Rejected</span>";
}
else
{
    echo "<span class='badge bg-primary'>Completed</span>";
}

?>

</td>

<td>

<?= $row['current_status'] ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php

include '../includes/footer.php';

?>