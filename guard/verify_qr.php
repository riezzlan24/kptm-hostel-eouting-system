<?php

include '../config/error.php';
include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guard') {
    header("Location: ../login.php");
    exit();
}

if(
    isset($_POST['qr_content'])
    &&
    !empty($_POST['qr_content'])
)
{
    $qr_content = $_POST['qr_content'];

    // Extract request ID
    $id = (int) str_replace(
        "OUTING_",
        "",
       trim($qr_content)
    );

    // Check request
    $stmt = mysqli_prepare(
        $conn,
        "SELECT *
        FROM outing_request
        WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result)==1)
    {
        $row = mysqli_fetch_assoc($result);

        if(
            $row['status']=="Approved"
            &&
            $row['current_status']=="Inside"
        )
        {
            header(
                "Location: checkout.php?id=".$id
            );
            exit();
        }

        elseif(
            $row['current_status']=="Outside"
        )
        {
            header(
                "Location: checkin.php?id=".$id
            );
            exit();
        }

        else
        {
            echo "QR already completed.";
        }

    }
    else
    {
        echo "Invalid QR Code.";
    }

    }
else
{
    header("Location: scanner.php");
    exit();
}

?>