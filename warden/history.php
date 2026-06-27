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

$search = "";

if(isset($_GET['search']))
{
    $search = $_GET['search'];
}

$sql = "
SELECT *
FROM outing_request
WHERE
student_id LIKE '%$search%'
OR
destination LIKE '%$search%'
OR
status LIKE '%$search%'
ORDER BY id DESC
";

$result = mysqli_query($conn,$sql);

?>

<h2 class="fw-bold mt-3">

Outing History

</h2>

<p class="text-muted">

View all outing activities and their current status.

</p>

<form method="GET" class="mb-4">

<div class="row">

<div class="col-md-5">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Student ID, destination or status..."
value="<?= $search ?>">

</div>

<div class="col-md-2">

<button
class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

</form>

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

<?php echo $row['student_id']; ?>

</td>

<td>

<?php echo $row['outing_date']; ?>

</td>

<td>

<?php echo $row['destination']; ?>

</td>

<td>

<?php

if($row['status']=="Pending")
{
?>

<span class="badge badge-pending px-3 py-2">

Pending

</span>

<?php
}
elseif($row['status']=="Approved")
{
?>

<span class="badge badge-approved px-3 py-2">

Approved

</span>

<?php
}
elseif($row['status']=="Rejected")
{
?>

<span class="badge badge-rejected px-3 py-2">

Rejected

</span>

<?php
}
else
{
?>

<span class="badge badge-completed px-3 py-2">

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

<span class="badge badge-outside px-3 py-2">

Outside

</span>

<?php
}
else
{
?>

<span class="badge badge-inside px-3 py-2">

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
