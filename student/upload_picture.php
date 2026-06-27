<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';

if(
    !isset($_SESSION['user_id'])
    ||
    $_SESSION['role']!='student'
)
{
    header(
        "Location: ../login.php"
    );
    exit();
}

$user_id =
$_SESSION['user_id'];

if(
    isset($_FILES['profile_picture'])
)
{
    $filename =
    time()
    .
    "_"
    .
    basename(
        $_FILES['profile_picture']['name']
    );

    $target =
    "../uploads/"
    .
    $filename;

    move_uploaded_file(
        $_FILES['profile_picture']['tmp_name'],
        $target
    );

    $sql =
    "
    UPDATE users
    SET profile_picture=?
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
        $filename,
        $user_id
    );

    mysqli_stmt_execute(
        $stmt
    );

    $_SESSION['profile_picture'] =
    $filename;
}

header(
    "Location: profile.php"
);

exit();

?>