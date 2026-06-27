<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$student_id = $_SESSION['user_id'];
$stmt = mysqli_prepare(
    $conn,
    "SELECT
        qr_pass.qr_code,
        outing_request.outing_date,
        outing_request.outing_time,
        outing_request.destination
    FROM qr_pass
    INNER JOIN outing_request
        ON qr_pass.request_id = outing_request.id
    WHERE outing_request.student_id = ?
    AND outing_request.status='Approved'
    ORDER BY outing_request.id DESC
    LIMIT 1"
);

if(!$stmt)
{
    die("Prepare failed.");
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<h1 class="mt-3">

QR Pass

</h1>

<hr>

<?php

if(mysqli_num_rows($result) > 0)
{
    $row = mysqli_fetch_assoc($result);

?>

<div class="card">

<div class="card-body text-center">

<h4>

Outing Information

</h4>

<hr>

<p>

<b>Date:</b>

<?php echo $row['outing_date']; ?>

</p>

<p>

<b>Time:</b>

<?php echo $row['outing_time']; ?>

</p>

<p>

<b>Destination:</b>

<?php echo $row['destination']; ?>

</p>

<hr>

<h4>

Student QR Code

</h4>

<img
src="../qr_codes/<?php echo $row['qr_code']; ?>"
width="300"
height="300"
alt="QR Code">

<p class="mt-3 text-muted">

Show this QR code to the guard for check-out and check-in.

</p>

</div>

</div>

<?php

}
else
{
?>

<div class="alert alert-warning">

No approved outing request.

</div>

<?php

}

include '../includes/footer.php';

?>