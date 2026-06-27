<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guard')
{
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

if(!isset($_GET['id']))
{
    die("Invalid request.");
}

$id = $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "
    UPDATE outing_request
    SET
        current_status='Outside',
        checkout_time=NOW()
    WHERE
        id=?
        AND status='Approved'
        AND current_status='Inside'
    "
);

if(!$stmt)
{
    die("Prepare failed.");
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

?>

<h2 class="fw-bold mt-3">

Check-Out Result

</h2>

<p class="text-muted">

Student departure verification.

</p>

<hr>

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-body p-5 text-center">

<?php

if(mysqli_stmt_execute($stmt))
{
?>

<i
class="bi bi-check-circle-fill text-success"
style="font-size:80px;">
</i>

<h3 class="mt-4 text-success">

Check-Out Successful

</h3>

<p class="text-muted">

Student has successfully left the hostel.

</p>

<?php

}
else
{
?>

<i
class="bi bi-x-circle-fill text-danger"
style="font-size:80px;">
</i>

<h3 class="mt-4 text-danger">

Check-Out Failed

</h3>

<p class="text-muted">

Unable to update student status.

</p>

<?php

}

?>

<div class="mt-4">

<a
href="scanner.php"
class="btn btn-primary px-4 py-2">

<i class="bi bi-arrow-left"></i>

Back to Scanner

</a>

</div>

</div>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>