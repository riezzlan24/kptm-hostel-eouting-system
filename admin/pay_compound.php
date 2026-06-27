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

if(!isset($_GET['id']))
{
    die("Invalid request.");
}

$id = $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "
    UPDATE compound
    SET
        status='Paid'
    WHERE
        id=?
    "
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

?>

<h2 class="fw-bold mt-3">

Compound Payment

</h2>

<p class="text-muted">

Update compound payment status.

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

Payment Recorded

</h3>

<p class="text-muted">

Compound status has been updated successfully.

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

Update Failed

</h3>

<p class="text-muted">

Unable to update compound status.

</p>

<?php

}

?>

<div class="mt-4">

<a
href="compound.php"
class="btn btn-primary">

<i class="bi bi-arrow-left"></i>

Back to Compound Management

</a>

</div>

</div>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>