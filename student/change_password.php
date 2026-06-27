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
    header("Location: ../login.php");
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$message = "";
$message_type = "";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $current_password =
    $_POST['current_password'];

    $new_password =
    $_POST['new_password'];

    $confirm_password =
    $_POST['confirm_password'];

    $user_id =
    $_SESSION['user_id'];

    $sql =
    "
    SELECT password
    FROM users
    WHERE id=?
    ";

    $stmt =
    mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute(
        $stmt
    );

    $result =
    mysqli_stmt_get_result(
        $stmt
    );

    $user =
    mysqli_fetch_assoc(
        $result
    );

    if(
        !password_verify(
            $current_password,
            $user['password']
        )
    )
    {
        $message =
        "Current password is incorrect.";

        $message_type =
        "danger";
    }
    elseif(
        $new_password !=
        $confirm_password
    )
    {
        $message =
        "New passwords do not match.";

        $message_type =
        "danger";
    }
    else
    {
        $hashed_password =
        password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $update_sql =
        "
        UPDATE users
        SET password=?
        WHERE id=?
        ";

        $update_stmt =
        mysqli_prepare(
            $conn,
            $update_sql
        );

        mysqli_stmt_bind_param(
            $update_stmt,
            "si",
            $hashed_password,
            $user_id
        );

        mysqli_stmt_execute(
            $update_stmt
        );

        $message =
        "Password changed successfully.";

        $message_type =
        "success";
    }
}

?>

<h2 class="fw-bold mt-3">

Change Password

</h2>

<p class="text-muted">

Update your account password.

</p>

<hr>

<?php

if(!empty($message))
{
?>

<div class="alert alert-<?= $message_type ?>">

<?= $message ?>

</div>

<?php
}

?>

<form method="POST">

<div class="card shadow-sm border-0 rounded-4">

<div class="card-body">

<div class="mb-3">

<label class="form-label">

Current Password

</label>

<input
type="password"
name="current_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

New Password

</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Confirm New Password

</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-primary">

Change Password

</button>

<a
href="profile.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</div>

</form>

<?php

include '../includes/footer.php';

?>