<?php

include '../config/error.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

include '../config/db.php';
include '../config/session.php';
include '../config/notification.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

if(isset($_POST['submit']))
{
    $student_id = $_SESSION['user_id'];

    $message = trim($_POST['message']);

    if(empty($message))
    {
        echo "<div class='alert alert-danger'>
        Message cannot be empty.
        </div>";
    }
    else
    {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO sos_alert
            (student_id, message)
            VALUES
            (?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $student_id,
            $message
        );

        if(mysqli_stmt_execute($stmt))
{
    $warden_sql =
    "
    SELECT id
    FROM users
    WHERE role='warden'
    ";

    $warden_result =
    mysqli_query(
        $conn,
        $warden_sql
    );

    while(
        $warden =
        mysqli_fetch_assoc(
            $warden_result
        )
    )
    {
    createNotification(
            $conn,
            $warden['id'],
            "SOS Emergency",
            "A student has sent an SOS emergency alert."
        );
    }

            echo "<div class='alert alert-success'>
            SOS alert sent successfully.
            </div>";
        }
        else
        {
            echo "<div class='alert alert-danger'>
            Failed to send SOS alert.
            </div>";
        }
    }
}

?>

<h1 class="mt-3">

SOS Emergency

</h1>

<hr>

<div class="card">

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label class="form-label">

Emergency Message

</label>

<textarea
name="message"
class="form-control"
rows="5"
required></textarea>

</div>

<button
type="submit"
name="submit"
class="btn btn-danger">

Send SOS

</button>

</form>

</div>

</div>

<?php

include '../includes/footer.php';

?>