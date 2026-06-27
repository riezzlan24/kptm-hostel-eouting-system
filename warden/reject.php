<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';
include '../config/notification.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warden') {
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id']))
{
    die("Invalid request.");
}

$id = $_GET['id'];

$get_student =
mysqli_prepare(
    $conn,
    "
    SELECT student_id
    FROM outing_request
    WHERE id=?
    "
);

mysqli_stmt_bind_param(
    $get_student,
    "i",
    $id
);

mysqli_stmt_execute(
    $get_student
);

$get_result =
mysqli_stmt_get_result(
    $get_student
);

$request =
mysqli_fetch_assoc(
    $get_result
);

$user_id =
(int)$request['student_id'];

$stmt = mysqli_prepare(
    $conn,
    "UPDATE outing_request
    SET status='Rejected'
    WHERE id=?"
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

if(mysqli_stmt_execute($stmt))
{
    createNotification(
    $conn,
    $user_id,
    "Outing Rejected",
    "Your outing request has been rejected."
    );
    header("Location: pending_request.php");
    exit();
}
else
{
    die("Failed to reject request.");
}

?>