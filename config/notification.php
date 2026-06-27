<?php

function createNotification(
    $conn,
    $user_id,
    $title,
    $message
)
{
    $sql =
    "
    INSERT INTO notifications
    (
        user_id,
        title,
        message
    )
    VALUES
    (
        ?,
        ?,
        ?
    )
    ";

    $stmt =
    mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $user_id,
        $title,
        $message
    );

    mysqli_stmt_execute(
        $stmt
    );
}

?>