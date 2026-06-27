<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if(
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['role']!='admin'
)
{
    header("Location: ../login.php");
    exit();
}

$search = "";
$programme = "";
$cohort = "";

$limit = 20;

$page = 1;

if(
    isset($_GET['page'])
)
{
    $page =
    (int)
    $_GET['page'];
}

$offset =
(
    $page - 1
)
*
$limit;

if(isset($_GET['search']))
{
    $search =
    trim($_GET['search']);
}

if(isset($_GET['programme']))
{
    $programme =
    trim($_GET['programme']);
}

if(isset($_GET['cohort']))
{
    $cohort =
    trim($_GET['cohort']);
}

$sql =
"
SELECT
id,
student_id,
fullname,
email,
role,
programme,
cohort,
status
FROM users
WHERE
(
    student_id LIKE ?
    OR
    fullname LIKE ?
)
AND
programme LIKE ?
AND
cohort LIKE ?
ORDER BY fullname
LIMIT ?, ?
";

$stmt =
mysqli_prepare(
    $conn,
    $sql
);

$keyword =
"%".$search."%";

$programme_filter =
"%".$programme."%";

$cohort_filter =
"%".$cohort."%";

mysqli_stmt_bind_param(
    $stmt,
    "ssssii",
    $keyword,
    $keyword,
    $programme_filter,
    $cohort_filter,
    $offset,
    $limit
);

mysqli_stmt_execute(
    $stmt
);

$result =
mysqli_stmt_get_result(
    $stmt
);

$count_sql =
"
SELECT
COUNT(*) AS total
FROM users
WHERE
(
    student_id LIKE ?
    OR
    fullname LIKE ?
)
AND
programme LIKE ?
AND
cohort LIKE ?
";

$count_stmt =
mysqli_prepare(
    $conn,
    $count_sql
);

mysqli_stmt_bind_param(
    $count_stmt,
    "ssss",
    $keyword,
    $keyword,
    $programme_filter,
    $cohort_filter
);

mysqli_stmt_execute(
    $count_stmt
);

$count_result =
mysqli_stmt_get_result(
    $count_stmt
);

$total_rows =
mysqli_fetch_assoc(
    $count_result
)['total'];

$total_pages =
ceil(
    $total_rows /
    $limit
);

$programme_sql =
"
SELECT DISTINCT programme
FROM users
WHERE programme <> ''
ORDER BY programme
";

$programme_result =
mysqli_query(
    $conn,
    $programme_sql
);

$cohort_sql =
"
SELECT DISTINCT cohort
FROM users
WHERE cohort <> ''
ORDER BY cohort
";

$cohort_result =
mysqli_query(
    $conn,
    $cohort_sql
);

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

?>

<h2 class="fw-bold mt-3">

User Management

</h2>

<p class="text-muted">

Manage student and system users.

</p>

<hr>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="reset"
)
{
?>

<div class="alert alert-success">

Password successfully reset to
<b>student123</b>.

</div>

<?php
}

?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="reset"
)
{
?>

<div class="alert alert-success">

Password successfully reset to
<b>student123</b>.

</div>

<?php
}

?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="delete"
)
{
?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="deactivate"
)
{
?>

<div class="alert alert-warning">

User successfully marked as Inactive.

</div>

<?php
}

?>

<div class="alert alert-danger">

User successfully deleted.

</div>

<?php
}

?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="graduate"
)
{
?>

<div class="alert alert-secondary">

Student successfully marked as Graduated.

</div>

<?php
}

?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="batch_graduate"
)
{
?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="batch_activate"
)
{
?>

<?php

if(
    isset($_GET['success'])
    &&
    $_GET['success']=="update"
)
{
?>

<div class="alert alert-success">

User information successfully updated.

</div>

<?php
}

?>

<div class="alert alert-success">

Selected intake successfully marked as Active.

</div>

<?php
}

?>

<div class="alert alert-secondary">

Selected intake successfully marked as Graduated.

</div>

<?php
}

?>

<form method="GET">

<div class="row mb-4">

<div class="col-md-3">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Student ID or Name"
value="<?= $search ?>">

</div>

<div class="col-md-3">

<select
name="programme"
class="form-select">

<option value="">

All Programmes

</option>

<?php

while(
    $programme_row =
    mysqli_fetch_assoc(
        $programme_result
    )
)
{
?>

<option
value="<?= $programme_row['programme'] ?>"
<?= ($programme==$programme_row['programme']) ? "selected" : "" ?>>

<?php

switch($programme_row['programme'])
{
    case "CT103":
        echo "CT103 - Information Technology";
        break;

    case "CT108":
        echo "CT108 - Graphic Design";
        break;

    case "AB107":
        echo "AB107 - Business Administration";
        break;

    case "CC104":
        echo "CC104 - Computer Science";
        break;

    default:
        echo $programme_row['programme'];
}

?>

</option>

<?php
}
?>

</select>

</div>

<div class="col-md-2">

<select
name="cohort"
class="form-select">

<option value="">

All Intake

</option>

<?php

while(
    $cohort_row =
    mysqli_fetch_assoc(
        $cohort_result
    )
)
{
?>

<option
value="<?= $cohort_row['cohort'] ?>"
<?= ($cohort==$cohort_row['cohort']) ? "selected" : "" ?>>

<?= $cohort_row['cohort'] ?>

</option>

<?php
}
?>

</select>

</div>

<div class="col-md-4">

<div class="d-flex gap-2">

<button
type="submit"
class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

<a
href="graduate_batch.php?programme=<?= $programme ?>&cohort=<?= $cohort ?>"
class="btn btn-secondary"
onclick="return confirm('Mark all selected students as Graduated?')">

<i class="bi bi-mortarboard"></i>

Graduate Intake

</a>

<a
href="activate_batch.php?programme=<?= $programme ?>&cohort=<?= $cohort ?>"
class="btn btn-success"
onclick="return confirm('Mark all selected students as Active?')">

<i class="bi bi-arrow-clockwise"></i>

Activate Intake

</a>

</div>

</div>

</div>

</form>

<div class="card border-0 shadow-sm rounded-4">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>

Student ID

</th>

<th>

Full Name

</th>

<th>

Email

</th>

<th>

Role

</th>

<th>

Programme

</th>

<th>

Intake

</th>

<th>

Status

</th>

<th>

Action

</th>

</tr>

</thead>

<tbody>

<?php

while(
    $row =
    mysqli_fetch_assoc(
        $result
    )
)
{
?>

<tr>

<td>

<?= $row['student_id'] ?>

</td>

<td>

<?= $row['fullname'] ?>

</td>

<td>

<?= $row['email'] ?>

</td>

<td>

<?= ucfirst($row['role']) ?>

</td>

<td>

<?= $row['programme'] ?>

</td>

<td>

<?php

if(
    empty(
        $row['cohort']
    )
)
{
?>

<span class="badge bg-danger">

No Intake

</span>

<?php
}
else
{
    echo $row['cohort'];
}

?>

</td>

<td>

<?php

if(
    $row['status']=="Active"
)
{
?>

<span class="badge bg-success">

Active

</span>

<?php
}
elseif(
    $row['status']=="Graduated"
)
{
?>

<span class="badge bg-secondary">

Graduated

</span>

<?php
}
else
{
?>

<span class="badge bg-danger">

Inactive

</span>

<?php
}

?>

</td>

<td>

<div class="btn-group btn-group-sm">

<a
href="edit_user.php?id=<?= $row['id'] ?>"
class="btn btn-outline-warning"
title="Edit">

<i class="bi bi-pencil"></i>

</a>

<a
href="reset_password.php?id=<?= $row['id'] ?>"
class="btn btn-outline-info"
title="Reset Password">

<i class="bi bi-key"></i>

</a>

<a
href="graduate_user.php?id=<?= $row['id'] ?>"
class="btn btn-outline-secondary"
title="Graduate"
onclick="return confirm('Mark this student as graduated?')">

<i class="bi bi-mortarboard"></i>

</a>

<a
href="delete_user.php?id=<?= $row['id'] ?>"
class="btn btn-outline-danger"
title="Delete"
onclick="return confirm('Delete this user?')">

<i class="bi bi-trash"></i>

</a>

</div>

</td>

</tr>

<?php
}
?>

</tbody>

</table>

<nav>

<ul class="pagination justify-content-center">

<?php

if(
    $page > 1
)
{
?>

<li class="page-item">

<a
class="page-link"
href="?page=<?= $page-1 ?>
&search=<?= urlencode($search) ?>
&programme=<?= urlencode($programme) ?>
&cohort=<?= urlencode($cohort) ?>">

Previous

</a>

</li>

<?php
}

for(
    $i = max(1,$page-2);
    $i <= min($total_pages,$page+2);
    $i++
)
{
?>

<li
class="page-item <?= ($page==$i) ? 'active' : '' ?>">

<a
class="page-link"
href="?page=<?= $i ?>
&search=<?= urlencode($search) ?>
&programme=<?= urlencode($programme) ?>
&cohort=<?= urlencode($cohort) ?>">

<?= $i ?>

</a>

</li>

<?php
}

if(
    $page < $total_pages
)
{
?>

<li class="page-item">

<a
class="page-link"
href="?page=<?= $page+1 ?>
&search=<?= urlencode($search) ?>
&programme=<?= urlencode($programme) ?>
&cohort=<?= urlencode($cohort) ?>">

Next

</a>

</li>

<?php
}
?>

</ul>

</nav>

</div>

</div>

</div>

<?php

include '../includes/footer.php';

?>