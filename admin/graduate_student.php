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

$programme =
$_GET['programme'] ?? "";

$cohort =
$_GET['cohort'] ?? "";

if(
    empty($programme)
    ||
    empty($cohort)
)
{
    header(
        "Location: user_management.php"
    );
    exit();
}

$sql =
"
UPDATE users
SET status='Graduated'
WHERE
programme=?
AND
cohort=?
";

$stmt =
mysqli_prepare(
    $conn,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $programme,
    $cohort
);

mysqli_stmt_execute(
    $stmt
);

header(
    "Location: user_management.php?success=batch_graduate"
);

exit();

?>