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
    header(
        "Location: ../login.php"
    );
    exit();
}

$id =
(int)
$_POST['id'];

$fullname =
trim(
    $_POST['fullname']
);

$email =
trim(
    $_POST['email']
);

$programme =
trim(
    $_POST['programme']
);

$cohort =
trim(
    $_POST['cohort']
);

$status =
trim(
    $_POST['status']
);

$sql =
"
UPDATE users
SET
fullname=?,
email=?,
programme=?,
cohort=?,
status=?
WHERE id=?
";

$stmt =
mysqli_prepare(
    $conn,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "sssssi",
    $fullname,
    $email,
    $programme,
    $cohort,
    $status,
    $id
);

mysqli_stmt_execute(
    $stmt
);

header(
    "Location: user_management.php?success=update"
);

exit();

?>