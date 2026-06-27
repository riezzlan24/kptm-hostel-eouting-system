<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

?>

<h1 class="mt-3">

Student Profile

</h1>

<hr>

<?php

if(
    empty(
        $row['profile_picture']
    )
)
{
    $picture =
    "../uploads/default.png";
}
else
{
    $picture =
    "../uploads/"
    .
    $row['profile_picture'];
}

?>

<div class="text-center mb-4">

<img
src="<?= $picture ?>"
class="rounded-circle border shadow"
width="150"
height="150">

</div>

<form
method="POST"
action="upload_picture.php"
enctype="multipart/form-data"
class="text-center mb-4">

<input
type="file"
name="profile_picture"
class="form-control mb-3"
required>

<button
type="submit"
class="btn btn-primary">

Upload Picture

</button>

</form>

<div class="card">

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="30%">

Student ID

</th>

<td>

<?php echo $row['student_id']; ?>

</td>

</tr>

<tr>

<th>

Full Name

</th>

<td>

<?php echo $row['fullname']; ?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?php echo $row['email']; ?>

</td>

</tr>

<tr>

<th>

Role

</th>

<td>

<span class="badge bg-primary">

<?php echo ucfirst($row['role']); ?>

</span>

</td>

</tr>

</table>

</div>

</div>

<?php

include '../includes/footer.php';

?>