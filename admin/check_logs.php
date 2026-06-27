<?php

include '../config/db.php';

$sql = "SELECT * FROM check_log
        ORDER BY id DESC";

$result = mysqli_query($conn,$sql);

?>

<h1>Check Logs</h1>

<table border="1">

<tr>
<th>ID</th>
<th>Request ID</th>
<th>Action</th>
<th>Time</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= $row['request_id'] ?></td>

<td><?= $row['action'] ?></td>

<td><?= $row['action_time'] ?></td>

</tr>

<?php } ?>

</table>