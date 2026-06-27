<div class="container-fluid">

<div class="row">

<div
class="col-md-2 p-4"
style="
background:#F5F5F7;
min-height:100vh;
border-right:1px solid #E5E5E7;
">

<h5
class="fw-bold mb-4"
style="color:#1D1D1F;">

Navigation

</h5>

<?php

$current_page = basename($_SERVER['PHP_SELF']);

$link_style = "
display:flex;
align-items:center;
gap:14px;
padding:14px 18px;
margin-bottom:10px;
border-radius:18px;
text-decoration:none;
font-weight:500;
font-size:17px;
transition:.3s;
";

function activeStyle($page, $current_page)
{
    global $link_style;

    return $link_style .
    (
        $current_page == $page
        ?
        "background:white;
        box-shadow:0 5px 15px rgba(0,0,0,.05);
        color:#D86A6A;"
        :
        "color:#1D1D1F;"
    );
}

if($_SESSION['role']=="student")
{
?>

<a href="../student/dashboard.php" style="<?= activeStyle('dashboard.php',$current_page) ?>">
<i class="bi bi-house"></i>
<span>Dashboard</span>
</a>

<a href="../student/apply_outing.php" style="<?= activeStyle('apply_outing.php',$current_page) ?>">
<i class="bi bi-pencil-square"></i>
<span>Apply Outing</span>
</a>

<a href="../student/my_request.php" style="<?= activeStyle('my_request.php',$current_page) ?>">
<i class="bi bi-card-list"></i>
<span>My Requests</span>
</a>

<a href="../student/qr_pass.php" style="<?= activeStyle('qr_pass.php',$current_page) ?>">
<i class="bi bi-qr-code"></i>
<span>QR Pass</span>
</a>

<a href="../student/compound.php" style="<?= activeStyle('compound.php',$current_page) ?>">
<i class="bi bi-receipt"></i>
<span>Compound</span>
</a>

<a href="../student/sos.php" style="<?= activeStyle('sos.php',$current_page) ?>">
<i class="bi bi-exclamation-triangle"></i>
<span>SOS Emergency</span>
</a>

<?php
}

elseif($_SESSION['role']=="warden")
{
?>

<a href="../warden/dashboard.php" style="<?= activeStyle('dashboard.php',$current_page) ?>">
<i class="bi bi-house"></i>
<span>Dashboard</span>
</a>

<a href="../warden/pending_request.php" style="<?= activeStyle('pending_request.php',$current_page) ?>">
<i class="bi bi-file-earmark-text"></i>
<span>Pending Requests</span>
</a>

<a href="../warden/active_students.php" style="<?= activeStyle('active_students.php',$current_page) ?>">
<i class="bi bi-people"></i>
<span>Active Students</span>
</a>

<a href="../warden/history.php" style="<?= activeStyle('history.php',$current_page) ?>">
<i class="bi bi-clock-history"></i>
<span>History</span>
</a>

<a href="../warden/sos_alert.php" style="<?= activeStyle('sos_alert.php',$current_page) ?>">
<i class="bi bi-bell"></i>
<span>SOS Alerts</span>
</a>

<?php
}

elseif($_SESSION['role']=="guard")
{
?>

<a href="../guard/dashboard.php" style="<?= activeStyle('dashboard.php',$current_page) ?>">
<i class="bi bi-house"></i>
<span>Dashboard</span>
</a>

<a href="../guard/scanner.php" style="<?= activeStyle('scanner.php',$current_page) ?>">
<i class="bi bi-upc-scan"></i>
<span>QR Scanner</span>
</a>

<?php
}

elseif($_SESSION['role']=="admin")
{
?>

<a href="../admin/dashboard.php" style="<?= activeStyle('dashboard.php',$current_page) ?>">
<i class="bi bi-house"></i>
<span>Dashboard</span>
</a>

<a href="../admin/student_monitoring.php" style="<?= activeStyle('student_monitoring.php',$current_page) ?>">
<i class="bi bi-person-lines-fill"></i>
<span>Student Monitoring</span>
</a>

<a href="../admin/user_management.php" style="<?= activeStyle('user_management.php',$current_page) ?>">
<i class="bi bi-people-fill"></i>
<span>User Management</span>
</a>

<a href="../admin/analytics.php" style="<?= activeStyle('analytics.php',$current_page) ?>">
<i class="bi bi-bar-chart"></i>
<span>Analytics</span>
</a>

<a href="../admin/upload_student.php" style="<?= activeStyle('upload_student.php',$current_page) ?>">
<i class="bi bi-upload"></i>
<span>Upload Student Data</span>
</a>

<a
href="../admin/compound.php"
style="<?= activeStyle('compound.php',$current_page) ?>">

<i class="bi bi-file-earmark-text"></i>

<span>Compound Management</span>

</a>

<?php
}

?>

<hr>

</div>

<div class="col-md-10 p-4">