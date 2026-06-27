<?php

include '../config/db.php';
include '../config/session.php';

if(
    !isset($_SESSION['user_id'])
)
{
    header("Location: ../login.php");
    exit();
}

if(
    !isset($_GET['id'])
)
{
    header("Location: dashboard.php");
    exit();
}

$id =
(int)$_GET['id'];

/*
|--------------------------------------------------------------------------
| Mark notification as read
|--------------------------------------------------------------------------
*/

$stmt =
mysqli_prepare(
    $conn,
    "
    UPDATE notifications
    SET is_read=1
    WHERE id=?
    AND user_id=?
    "
);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $id,
    $_SESSION['user_id']
);

mysqli_stmt_execute(
    $stmt
);

/*
|--------------------------------------------------------------------------
| Get notification title
|--------------------------------------------------------------------------
*/

$get =
mysqli_prepare(
    $conn,
    "
    SELECT title
    FROM notifications
    WHERE id=?
    "
);

mysqli_stmt_bind_param(
    $get,
    "i",
    $id
);

mysqli_stmt_execute(
    $get
);

$result =
mysqli_stmt_get_result(
    $get
);

$notification =
mysqli_fetch_assoc(
    $result
);

/*
|--------------------------------------------------------------------------
| Redirect by role & notification type
|--------------------------------------------------------------------------
*/

if($_SESSION['role']=="student")
{
    if(
        $notification['title']=="Compound Issued"
    )
    {
        header("Location: compound.php");
    }
    else
    {
        header("Location: my_request.php");
    }
}
elseif($_SESSION['role']=="warden")
{
    if(
        $notification['title']=="SOS Emergency"
    )
    {
        header("Location: sos_alert.php");
    }
    else
    {
        header("Location: pending_request.php");
    }
}
elseif($_SESSION['role']=="guard")
{
    header("Location: scanner.php");
}
elseif($_SESSION['role']=="admin")
{
    header("Location: dashboard.php");
}

exit();

?>