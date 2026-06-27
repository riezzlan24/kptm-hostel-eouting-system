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

if(
    !isset($_GET['id'])
)
{
    header(
        "Location: user_management.php"
    );
    exit();
}

$id =
(int)
$_GET['id'];

$password =
password_hash(
    "student123",
    PASSWORD_DEFAULT
);

$sql =
"
UPDATE users
SET password=?
WHERE id=?
";

$stmt =
mysqli_prepare(
    $conn,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $password,
    $id
);

mysqli_stmt_execute(
    $stmt
);

header(
    "Location: user_management.php?success=reset"
);

exit();

?>