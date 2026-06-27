<?php
include '../config/error.php';

include '../config/db.php';
include '../config/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['submit']))
{
    $student_id = $_SESSION['user_id'];

        //Check if student already has a pending request

        $check_stmt = mysqli_prepare(
            $conn,
            "
            SELECT id
            FROM outing_request
            WHERE student_id=?
            AND status='Pending'
            "
        );

        mysqli_stmt_bind_param(
            $check_stmt,
            "i",
            $student_id
        );

        mysqli_stmt_execute(
            $check_stmt
        );

        $check_result =
        mysqli_stmt_get_result(
            $check_stmt
        );

        if(mysqli_num_rows($check_result)>0)
        {
            echo "
            <script>

            alert(
            'You already have a pending outing request.'
            );

            window.location='my_request.php';

            </script>
            ";

            exit();
        }


    $outing_date = $_POST['outing_date'];
    $outing_time = $_POST['outing_time'];
    $destination = trim($_POST['destination']);
    $purpose = trim($_POST['purpose']);
    $vehicle_detail = trim($_POST['vehicle_detail']);
    $emergency_contact = trim($_POST['emergency_contact']);

    // Validate required fields
    if(
        empty($outing_date) ||
        empty($outing_time) ||
        empty($destination) ||
        empty($purpose) ||
        empty($emergency_contact)
    )
    {
        die("Please fill in all required fields.");
    }

    // Validate emergency contact number
    if(!preg_match('/^[0-9]{10,11}$/', $emergency_contact))
    {
        die("Invalid emergency contact number.");
    }

    // Validate destination length
    if(strlen($destination) > 255)
    {
        die("Destination is too long.");
    }

    // Validate purpose length
    if(strlen($purpose) > 1000)
    {
        die("Purpose is too long.");
    }

    // Validate picture upload
    if(empty($_FILES['picture']['name']))
    {
        die("Please upload a picture.");
    }

    // Generate unique filename
    $picture = time() . "_" . basename($_FILES['picture']['name']);

    // Allowed extensions
    $allowed = ['jpg', 'jpeg', 'png'];

    $extension = strtolower(
        pathinfo(
            $picture,
            PATHINFO_EXTENSION
        )
    );

    if(!in_array($extension, $allowed))
    {
        die("Only JPG, JPEG and PNG files are allowed.");
    }

    // Maximum file size: 2MB
    if($_FILES['picture']['size'] > 2000000)
    {
        die("File size must be less than 2MB.");
    }

    // Upload file
    if(
        !move_uploaded_file(
            $_FILES['picture']['tmp_name'],
            "../uploads/" . $picture
        )
    )
    {
        die("Failed to upload picture.");
    }

    // Insert data using prepared statement
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO outing_request
        (
            student_id,
            outing_date,
            outing_time,
            destination,
            purpose,
            vehicle_detail,
            picture,
            emergency_contact
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    if(!$stmt)
    {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "isssssss",
        $student_id,
        $outing_date,
        $outing_time,
        $destination,
        $purpose,
        $vehicle_detail,
        $picture,
        $emergency_contact
    );

    if(mysqli_stmt_execute($stmt))
    {
        header("Location: my_request.php");
        exit();
    }
    else
    {
        echo "Error: " . mysqli_error($conn);
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

?>
<h1 class="mt-3">
Apply Outing
</h1>

<hr>

<div class="card">

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">

Date

</label>

<input type="date"
name="outing_date"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Time

</label>

<input type="time"
name="outing_time"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Destination

</label>

<input type="text"
name="destination"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Purpose

</label>

<textarea
name="purpose"
class="form-control"
rows="4"
required></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Vehicle Detail

</label>

<input type="text"
name="vehicle_detail"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">

Upload Picture

</label>

<input type="file"
name="picture"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Emergency Contact

</label>

<input type="text"
name="emergency_contact"
class="form-control"
required>

</div>

<button
type="submit"
name="submit"
class="btn btn-primary">

Submit

</button>

</form>

</div>

</div>

<?php

include '../includes/footer.php';

?>