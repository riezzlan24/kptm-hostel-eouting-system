<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if (
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['role'] != 'student'
)
{
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';



// Pending Requests

$sql_pending = "
SELECT COUNT(*) total
FROM outing_request
WHERE student_id=?
AND status='Pending'
";

$stmt_pending = mysqli_prepare(
    $conn,
    $sql_pending
);

mysqli_stmt_bind_param(
    $stmt_pending,
    "i",
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt_pending
);

$result_pending =
mysqli_stmt_get_result(
    $stmt_pending
);

$pending =
mysqli_fetch_assoc(
    $result_pending
)['total'];



// Approved Requests

$sql_approved = "
SELECT COUNT(*) total
FROM outing_request
WHERE student_id=?
AND status='Approved'
";

$stmt_approved = mysqli_prepare(
    $conn,
    $sql_approved
);

mysqli_stmt_bind_param(
    $stmt_approved,
    "i",
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt_approved
);

$result_approved =
mysqli_stmt_get_result(
    $stmt_approved
);

$approved =
mysqli_fetch_assoc(
    $result_approved
)['total'];



// Completed Requests

$sql_completed = "
SELECT COUNT(*) total
FROM outing_request
WHERE student_id=?
AND status='Completed'
";

$stmt_completed = mysqli_prepare(
    $conn,
    $sql_completed
);

mysqli_stmt_bind_param(
    $stmt_completed,
    "i",
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt_completed
);

$result_completed =
mysqli_stmt_get_result(
    $stmt_completed
);

$completed =
mysqli_fetch_assoc(
    $result_completed
)['total'];



// Outstanding Compound

$sql_compound = "
SELECT SUM(amount) total
FROM compound
WHERE student_id=?
AND status='Pending'
";

$stmt_compound = mysqli_prepare(
    $conn,
    $sql_compound
);

mysqli_stmt_bind_param(
    $stmt_compound,
    "i",
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt_compound
);

$result_compound =
mysqli_stmt_get_result(
    $stmt_compound
);

$row_compound =
mysqli_fetch_assoc(
    $result_compound
);

$total_compound =
$row_compound['total'] ?? 0;



// Current Status

$sql_status = "
SELECT current_status
FROM outing_request
WHERE student_id=?
ORDER BY id DESC
LIMIT 1
";

$stmt_status = mysqli_prepare(
    $conn,
    $sql_status
);

mysqli_stmt_bind_param(
    $stmt_status,
    "i",
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt_status
);

$result_status =
mysqli_stmt_get_result(
    $stmt_status
);

$row_status =
mysqli_fetch_assoc(
    $result_status
);

$current_status =
$row_status['current_status'] ?? 'Inside';

?>

<h2
class="fw-bold"
style="color:#1D1D1F;">

Welcome back,

<?php echo $_SESSION['fullname']; ?>

👋

</h2>

<p class="text-muted">

Manage your hostel outing activities conveniently.

</p>

<br>

<div class="row g-4 mb-5">

<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center">

<h1>

<?php echo $pending; ?>

</h1>

<p class="text-muted mb-0">

Pending Requests

</p>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center">

<h1>

<?php echo $approved; ?>

</h1>

<p class="text-muted mb-0">

Approved Requests

</p>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center">

<h1>

<?php echo $completed; ?>

</h1>

<p class="text-muted mb-0">

Completed Requests

</p>

</div>

</div>

</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center">

<h1
style="
color:
<?= ($total_compound > 0)
?
'#DC3545'
:
'#28A745'
?>;
">

RM

<?= number_format($total_compound,2) ?>

</h1>

<p class="text-muted mb-0">

Outstanding Compound

</p>

</div>

</div>

</div>

</div>

<div class="row g-4">

<div class="col-md-4">

<a
href="apply_outing.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-pencil-square"
style="
font-size:50px;
color:#D86A6A;
">
</i>

<h4 class="mt-3">

Apply Outing

</h4>

<p class="text-muted">

Submit a new outing request.

</p>

</div>

</div>

</a>

</div>



<div class="col-md-4">

<a
href="my_request.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-card-list"
style="
font-size:50px;
color:#D86A6A;
">
</i>

<h4 class="mt-3">

My Requests

</h4>

<p class="text-muted">

View request history.

</p>

</div>

</div>

</a>

</div>



<div class="col-md-4">

<a
href="qr_pass.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-qr-code"
style="
font-size:50px;
color:#D86A6A;
">
</i>

<h4 class="mt-3">

QR Pass

</h4>

<p class="text-muted">

Display your QR pass.

</p>

</div>

</div>

</a>

</div>



<div class="col-md-4">

<a
href="profile.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-person"
style="
font-size:50px;
color:#D86A6A;
">
</i>

<h4 class="mt-3">

Profile

</h4>

<p class="text-muted">

Manage personal information.

</p>

</div>

</div>

</a>

</div>



<div class="col-md-4">

<a
href="compound.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-receipt"
style="
font-size:50px;
color:#D86A6A;
">
</i>

<h4 class="mt-3">

Compound

</h4>

<p class="text-muted">

View outstanding compounds.

</p>

</div>

</div>

</a>

</div>



<div class="col-md-4">

<a
href="sos.php"
class="text-decoration-none">

<div class="card border-0 shadow-sm rounded-4 h-100">

<div class="card-body text-center p-5">

<i
class="bi bi-exclamation-triangle"
style="
font-size:50px;
color:#DC3545;
">
</i>

<h4 class="mt-3">

SOS Emergency

</h4>

<p class="text-muted">

Send emergency alerts.

</p>

</div>

</div>

</a>

</div>

</div>

<div class="row mt-5">

<div class="col-md-6">

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body text-center p-4">

<h4>

Current Outing Status

</h4>

<hr>

<?php

if($current_status=="Outside")
{
?>

<h5 class="text-muted">

Return Before

</h5>

<h2 id="deadline">

Loading...

</h2>

<br>

<h5 class="text-muted">

Time Remaining

</h5>

<h1
id="countdown"
style="
color:#D86A6A;
font-weight:700;
">

Loading...

</h1>

<?php
}
else
{
?>

<i
class="bi bi-house-check-fill text-success"
style="font-size:70px;">
</i>

<h3 class="mt-4 text-success">

Inside Hostel

</h3>

<p class="text-muted">

No active outing session.

</p>

<?php
}

?>

</div>

</div>

</div>

</div>

<script>

var now = new Date();

var day = now.getDay();

var deadlineHour;

if(day >= 1 && day <= 4)
{
    deadlineHour = 22;
}
else
{
    deadlineHour = 23;
}

var deadline = new Date();

deadline.setHours(
    deadlineHour,
    0,
    0,
    0
);

function updateCountdown()
{
    var current = new Date();

    var distance =
    deadline - current;

    if(distance <= 0)
    {
        document
        .getElementById(
            "countdown"
        )
        .innerHTML =
        "OVERDUE";

        document
        .getElementById(
            "countdown"
        )
        .style.color =
        "#DC3545";

        return;
    }

    var hours =
    Math.floor(
        distance/(1000*60*60)
    );

    var minutes =
    Math.floor(
        (
            distance%(1000*60*60)
        )/(1000*60)
    );

    var seconds =
    Math.floor(
        (
            distance%(1000*60)
        )/1000
    );

    document
    .getElementById(
        "countdown"
    )
    .innerHTML =
    hours +
    " : " +
    minutes +
    " : " +
    seconds;
}



if(document.getElementById("deadline"))
{
    if(deadlineHour==22)
    {
        document
        .getElementById(
            "deadline"
        )
        .innerHTML =
        "10:00 PM";
    }
    else
    {
        document
        .getElementById(
            "deadline"
        )
        .innerHTML =
        "11:00 PM";
    }

    updateCountdown();

    setInterval(
        updateCountdown,
        1000
    );
}

</script>

<?php

include '../includes/footer.php';

?>
