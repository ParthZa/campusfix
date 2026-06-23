<?php
include("../includes/auth.php");
include("../config/db.php");

$complaint_id = $_GET['id'];
$staff_id = $_SESSION['user_id'];

$file_error = "";

$get_old = mysqli_query($conn, "SELECT status FROM complaints WHERE complaint_id='$complaint_id'");
$oldrow = mysqli_fetch_assoc($get_old);
$old_status = $oldrow['status'];
$status_options = "";

if ($old_status == "Assigned") {
    $status_options .= "<option value='In Progress'>In Progress</option>";
} elseif ($old_status == "In Progress") {
    $status_options .= "<option value='Resolved'>Resolved</option>";
    $status_options .= "<option value='Escalated'>Escalated</option>";
} elseif ($old_status == "Escalated") {
    $status_options .= "<option value='In Progress'>In Progress</option>";
} else {
    $status_options .= "<option value='Resolved'>Resolved</option>";
}

if (isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];
    $remarks = $_POST['remarks'];

    $action_file = "";

    if ($_FILES['action_file']['name'] != "") {
        $allowed_types = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['action_file']['name'];
        $file_size = $_FILES['action_file']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_types) && $file_size <= 2000000) {
            $filename = time() . "_" . rand(100, 999) . "." . $ext;
            $target = "../assets/uploads/action_proof/" . $filename;
            move_uploaded_file($_FILES['action_file']['tmp_name'], $target);
            $action_file = $filename;

            mysqli_query($conn, "INSERT INTO complaint_attachments(complaint_id,file_path,file_type)
            VALUES('$complaint_id','$action_file','action_proof')");
        } else {
            $file_error = "Only JPG, JPEG, PNG files under 2MB are allowed.";
        }
    }

    if ($file_error == "") {
        mysqli_query($conn, "UPDATE complaints SET status='$new_status' WHERE complaint_id='$complaint_id'");

        mysqli_query($conn, "INSERT INTO complaint_history(complaint_id,updated_by,old_status,new_status,remarks)
        VALUES('$complaint_id','$staff_id','$old_status','$new_status','$remarks')");

        header("Location: my_complaints.php");
    }
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2>Update Complaint Status</h2>
    <p><strong>Current Status:</strong> <?php echo $old_status; ?></p>
    <?php if ($file_error != "") { ?>
        <div class="alert alert-danger"><?php echo $file_error; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Select New Status</label>
            <select name="new_status" class="form-control" required>
                <?php echo $status_options; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Upload Action Proof (JPG/JPEG/PNG only, Max 2MB)</label>
            <input type="file" name="action_file" class="form-control">
        </div>

        <button type="submit" name="update_status" class="btn btn-primary">Update Complaint</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>