<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

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

$search = "";
$status_filter = "";

$sql = "
SELECT *
FROM compound
WHERE 1=1
";

$params = [];
$types = "";

if(isset($_GET['search']) && !empty($_GET['search']))
{
    $search = trim($_GET['search']);

    $sql .= " AND student_id LIKE ? ";

    $keyword = "%".$search."%";

    $params[] = $keyword;

    $types .= "s";
}

if(isset($_GET['status']) && !empty($_GET['status']))
{
    $status_filter = $_GET['status'];

    $sql .= " AND status=? ";

    $params[] = $status_filter;

    $types .= "s";
}

$sql .= " ORDER BY created_at DESC ";

$stmt = mysqli_prepare(
    $conn,
    $sql
);

if(!empty($params))
{
    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}

mysqli_stmt_execute($stmt);

$result =
mysqli_stmt_get_result($stmt);


?>

<h2 class="fw-bold mt-3">

Compound Management

</h2>

<form
method="GET"
class="mb-4">

<div class="row g-2">

<div class="col-md-3">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Student ID..."
value="<?= $search ?>">

</div>

<div class="col-md-3">

<select
name="status"
class="form-select">

<option value="">All Status</option>

<option
value="Pending"
<?= ($status_filter=="Pending") ? "selected" : "" ?>>

Pending

</option>

<option
value="Paid"
<?= ($status_filter=="Paid") ? "selected" : "" ?>>

Paid

</option>

</select>

</div>

<div class="col-md-2">

<button
class="btn btn-primary">

Search

</button>

</div>

<div class="col-md-2">

<a
href="compound.php"
class="btn btn-secondary">

Reset

</a>

</div>

</div>

</form>

<p class="text-muted">

Manage student compounds and payments.

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

<th>Reason</th>

<th>Amount</th>

<th>Status</th>

<th>Action</th>

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

<?= $row['reason'] ?>

</td>

<td>

RM <?= number_format($row['amount'],2) ?>

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
else
{
?>

<span class="badge badge-approved px-3 py-2">

Paid

</span>

<?php
}

?>

</td>

<td>

<?php

if($row['status']=="Pending")
{
?>

<a
href="pay_compound.php?id=<?= $row['id'] ?>"
class="btn btn-success btn-sm">

Mark Paid

</a>

<?php
}
else
{
?>

<i class="bi bi-check-circle-fill text-success"></i>

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