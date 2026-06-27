<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';
include '../config/notification.php';

$visitor_ip = $_SERVER['REMOTE_ADDR'];

$wifi_prefix = '10.234.192.';

if(
    strpos($visitor_ip,$wifi_prefix)!==0
    &&
    $visitor_ip!='127.0.0.1'
    &&
    $visitor_ip!='::1'
)
{
    include '../includes/header.php';
    include '../includes/navbar.php';
    include '../includes/sidebar.php';
?>

<h2 class="fw-bold mt-3">

KPTM Wi-Fi Required

</h2>

<hr>

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-body text-center p-5">

<i
class="bi bi-wifi-off text-danger"
style="font-size:80px;">
</i>

<h3 class="mt-4">

Access Denied

</h3>

<p class="text-muted">

Please connect to the KPTM Wi-Fi network before performing Check-In.

</p>

<a
href="scanner.php"
class="btn btn-primary">

Back to Scanner

</a>

</div>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

exit();

}

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

// Get student ID

$sql_student = "
SELECT student_id
FROM outing_request
WHERE id=?
";

$stmt_student = mysqli_prepare(
    $conn,
    $sql_student
);

mysqli_stmt_bind_param(
    $stmt_student,
    "i",
    $id
);

mysqli_stmt_execute(
    $stmt_student
);

$result_student =
mysqli_stmt_get_result(
    $stmt_student
);

$row_student =
mysqli_fetch_assoc(
    $result_student
);

$student_id =
$row_student['student_id'];



// Determine curfew

$current_day = date('N');

if(
    $current_day >= 1
    &&
    $current_day <= 4
)
{
    $deadline = "22:00:00";
}
else
{
    $deadline = "23:00:00";
}

$current_time = date('H:i:s');



// Update outing status

$stmt = mysqli_prepare(
    $conn,
    "
    UPDATE outing_request
    SET
        current_status='Inside',
        status='Completed',
        checkin_time=NOW()
    WHERE
        id=?
        AND current_status='Outside'
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

$compound_issued = false;

if($current_time > $deadline)
{
    // Check whether today's late-return compound already exists

    $check_compound = mysqli_prepare(
        $conn,
        "
        SELECT id
        FROM compound
        WHERE student_id=?
        AND reason='Late Return'
        AND DATE(created_at)=CURDATE()
        "
    );

    mysqli_stmt_bind_param(
        $check_compound,
        "i",
        $student_id
    );

    mysqli_stmt_execute(
        $check_compound
    );

    $compound_result =
    mysqli_stmt_get_result(
        $check_compound
    );

    // No duplicate found

    if(mysqli_num_rows($compound_result)==0)
    {
        $reason = "Late Return";

        $evidence =
        "Returned after curfew at "
        .
        date('H:i:s');

        $amount = 10.00;

        $status = "Pending";

        $insert_compound = mysqli_prepare(
            $conn,
            "
            INSERT INTO compound
            (
                student_id,
                reason,
                evidence,
                amount,
                status
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )
            "
        );

        mysqli_stmt_bind_param(
            $insert_compound,
            "issds",
            $student_id,
            $reason,
            $evidence,
            $amount,
            $status
        );

        mysqli_stmt_execute(
            $insert_compound
        );

        createNotification(
            $conn,
            $student_id,
            "Compound Issued",
            "A RM10 late return compound has been issued to your account."
        );

        $compound_issued = true;

        $compound_issued = true;
    }
}

?>

<h2 class="fw-bold mt-3">

Check-In Result

</h2>

<p class="text-muted">

Student return verification.

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

Check-In Successful

</h3>

<p class="text-muted">

Student has successfully returned to the hostel.

</p>

<?php

if($compound_issued)
{
?>

<div
class="alert alert-warning mt-4">

<h5>

<i class="bi bi-exclamation-triangle-fill"></i>

Late Return Detected

</h5>

<hr>

<p class="mb-0">

Compound Issued: RM10

</p>

</div>

<?php

}

?>

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

Check-In Failed

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