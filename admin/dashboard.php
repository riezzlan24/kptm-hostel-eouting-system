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

?>

<h2 class="fw-bold mt-3">

HEP Admin Dashboard

</h2>

<p class="text-muted">

Welcome back,

<b>

<?php echo $_SESSION['fullname']; ?>

</b>

</p>

<hr>

<div class="row g-4">

<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-people-fill"
style="
font-size:50px;
color:#D86A6A;
">

</i>

<h4 class="mt-3">

Student Monitoring

</h4>

<p class="text-muted">

Monitor all students and their status.

</p>

<a
href="student_monitoring.php"
class="btn btn-primary">

View Details

</a>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-calendar-check"
style="
font-size:50px;
color:#27AE60;
">

</i>

<h4 class="mt-3">

Outing Management

</h4>

<p class="text-muted">

Manage outing activities.

</p>

<a
href="outing_management.php"
class="btn btn-success">

View Details

</a>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-file-earmark-text"
style="
font-size:50px;
color:#E74C3C;
">

</i>

<h4 class="mt-3">

Report & Compound

</h4>

<p class="text-muted">

Manage reports and compounds.

</p>

<a
href="compound.php"
class="btn btn-danger">

View Details

</a>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-exclamation-triangle"
style="
font-size:50px;
color:#F39C12;
">

</i>

<h4 class="mt-3">

Emergency Management

</h4>

<p class="text-muted">

Handle SOS and emergency cases.

</p>

<a
href="emergency_management.php"
class="btn btn-warning">

View Details

</a>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-bar-chart-line"
style="
font-size:50px;
color:#3498DB;
">

</i>

<h4 class="mt-3">

Analytics & Report

</h4>

<p class="text-muted">

View reports and analytics.

</p>

<a
href="analytics.php"
class="btn btn-info">

View Details

</a>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card h-100">

<div class="card-body text-center p-4">

<i
class="bi bi-person-gear"
style="
font-size:50px;
color:#7F8C8D;
">

</i>

<h4 class="mt-3">

User Management

</h4>

<p class="text-muted">

Manage users and accounts.

</p>

<a
href="user_management.php"
class="btn btn-secondary">

View Details

</a>

</div>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>