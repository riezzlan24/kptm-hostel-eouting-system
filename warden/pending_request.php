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
FROM outing_request
WHERE status='Pending'
ORDER BY id DESC
";

$result = mysqli_query($conn,$sql);

?>

<h2 class="fw-bold mt-3">

Pending Requests

</h2>

<p class="text-muted">

Review and manage student outing applications.

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

<th>Date</th>

<th>Time</th>

<th>Destination</th>

<th class="text-center">

Action

</th>

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

<?php echo $row['outing_date']; ?>

</td>

<td>

<?php echo $row['outing_time']; ?>

</td>

<td>

<?php echo $row['destination']; ?>

</td>

<td class="text-center">

<a
href="approve.php?id=<?php echo $row['id']; ?>"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Approve

</a>

<a
href="reject.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger ms-2">

<i class="bi bi-x-circle"></i>

Reject

</a>

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