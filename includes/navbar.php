    <nav
    class="navbar navbar-expand-lg px-5 py-3"
    style="
    background:#FFFFFF;
    border-bottom:1px solid #EEEEEE;
    box-shadow:0 2px 12px rgba(0,0,0,.04);
    ">

    <div class="container-fluid">

    <!-- Left Side -->
    <div class="d-flex align-items-center">

    <img
    src="../assets/images/maracoporation.png"
    height="42"
    class="me-4">

    <img
    src="../assets/images/kemajuan.png"
    height="42"
    class="me-4">

    <img
    src="../assets/images/maralogo.png"
    height="42"
    class="me-4">

    <img
    src="../assets/images/logo2.png"
    height="42">

    </div>

    <!-- Right Side -->
    <div class="d-flex align-items-center">

    <?php

    $count_sql =
    "
    SELECT COUNT(*) AS total
    FROM notifications
    WHERE
    user_id=?
    AND
    is_read=0
    ";

    $count_stmt =
    mysqli_prepare(
        $conn,
        $count_sql
    );

    mysqli_stmt_bind_param(
        $count_stmt,
        "i",
        $_SESSION['user_id']
    );

    mysqli_stmt_execute(
        $count_stmt
    );

    $count_result =
    mysqli_stmt_get_result(
        $count_stmt
    );

    $notification_count =
    mysqli_fetch_assoc(
        $count_result
    )['total'];

    ?>

    <div class="dropdown me-4">

    <a
    href="#"
    class="position-relative text-dark"
    data-bs-toggle="dropdown">

    <i
    class="bi bi-bell"
    style="
    font-size:24px;
    "></i>

    <?php

    if(
        $notification_count>0
    )
    {
    ?>

    <span
    class="
    position-absolute
    top-0
    start-100
    translate-middle
    badge
    rounded-pill
    bg-danger
    ">

    <?= $notification_count ?>

    </span>

    <?php
    }

    ?>

    </a>

    <ul
    class="
    dropdown-menu
    dropdown-menu-end
    shadow
    "
    style="
    width:340px;
    ">

    <li
    class="
    dropdown-header
    fw-bold
    ">

    Notifications

    </li>

    <li>

    <hr
    class="dropdown-divider">

    </li>

    <?php

    $sql =
    "
    SELECT *
    FROM notifications
    WHERE user_id=?
    ORDER BY created_at DESC
    LIMIT 5
    ";

    $stmt =
    mysqli_prepare(
        $conn,
        $sql
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $_SESSION['user_id']
    );

    mysqli_stmt_execute(
        $stmt
    );

    $result =
    mysqli_stmt_get_result(
        $stmt
    );

    while(
        $notification =
        mysqli_fetch_assoc(
            $result
        )
    )
    {
    ?>

    <li>

    <a
    href="../<?= $_SESSION['role'] ?>/read_notification.php?id=<?= $notification['id'] ?>"
    class="dropdown-item">

    <div class="fw-semibold">

    <?= $notification['title'] ?>

    </div>

    <small class="text-muted">

    <?= $notification['message'] ?>

    </small>

    </a>

    </li>

    <?php
    }

    ?>

    </ul>

    </div>

    <div class="me-3 text-end">

    <div
    class="fw-semibold"
    style="
    color:#1D1D1F;
    font-size:18px;
    ">

    <?php echo $_SESSION['fullname']; ?>

    </div>

    <small
    style="
    color:#86868B;
    ">

    <?php echo ucfirst($_SESSION['role']); ?>

    </small>

    </div>

    <?php

    if(
        empty(
            $_SESSION['profile_picture']
        )
    )
    {
        $picture =
        "../uploads/default.png";
    }
    else
    {
        $picture =
        "../uploads/"
        .
        $_SESSION['profile_picture'];
    }

    ?>

    <div class="dropdown">

    <a
    class="dropdown-toggle"
    href="#"
    role="button"
    data-bs-toggle="dropdown"
    style="text-decoration:none;">

    <img
    src="<?= $picture ?>"
    style="
    width:50px;
    height:50px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid #E5E5E7;
    cursor:pointer;
    ">

    </a>

    <ul class="dropdown-menu dropdown-menu-end shadow">

    <li>

    <a
    class="dropdown-item"
    href="../<?= $_SESSION['role'] ?>/profile.php"

    <i class="bi bi-person-circle me-2"></i>

    My Profile

    </a>

    </li>

    <li>

    <a
    class="dropdown-item"
    hhref="../<?= $_SESSION['role'] ?>/change_password.php"

    <i class="bi bi-key me-2"></i>

    Change Password

    </a>

    </li>

    <li><hr class="dropdown-divider"></li>

    <li>

    <a
    class="dropdown-item text-danger"
    href="../logout.php">

    <i class="bi bi-box-arrow-right me-2"></i>

    Logout

    </a>

    </li>

    </ul>

    </div>

    </div>

    </div>

    </nav>