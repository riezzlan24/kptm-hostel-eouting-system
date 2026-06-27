<?php

require '../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';
include '../config/notification.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warden') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
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

// Approve request
$stmt = mysqli_prepare(
    $conn,
    "UPDATE outing_request
    SET status='Approved'
    WHERE id=?"
);

if (!$stmt) {
    die("Prepare failed.");
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if (mysqli_stmt_execute($stmt))
{
    // QR content
    $qrContent = "OUTING_" . $id;

    // Create QR
    $qrCode = new QrCode($qrContent);

    // Writer
    $writer = new PngWriter();

    // Generate result
    $result = $writer->write($qrCode);

    // Filename
    $filename = "qr_" . $id . ".png";

    // Save QR image
    $result->saveToFile(
        "../qr_codes/" . $filename
    );

    // Save filename into database
    $stmt2 = mysqli_prepare(
        $conn,
        "INSERT INTO qr_pass
        (request_id, qr_code)
        VALUES (?, ?)"
    );

    if (!$stmt2) {
        die("Prepare failed.");
    }

    mysqli_stmt_bind_param(
        $stmt2,
        "is",
        $id,
        $filename
    );

    mysqli_stmt_execute($stmt2);

    createNotification(
    $conn,
    $user_id,
    "Outing Approved",
    "Your outing request has been approved."
    );

    header("Location: pending_request.php");
    exit();
}
else
{
    die("Failed to approve request.");
}

?>