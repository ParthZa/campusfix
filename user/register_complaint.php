<?php
include("../includes/auth.php");
include("../config/db.php");
$edit_id = "";
$edit_title = "";
$edit_description = "";
$edit_category = "";
$edit_area = "";
$edit_priority = "";
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];

    $edata = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM complaints WHERE complaint_id='$edit_id'"));

    $edit_title = $edata['title'];
    $edit_description = $edata['description'];
    $edit_category = $edata['category_id'];
    $edit_area = $edata['area_id'];
    $edit_priority = $edata['priority'];
}

$file_error = "";
$success = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == "added") {
        $success = "Complaint Registered Successfully. Complaint ID: " . $_GET['cid'];
    } elseif ($_GET['msg'] == "updated") {
        $success = "Complaint Updated Successfully.";
    }
}

if (isset($_POST['submit_complaint'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $area_id = $_POST['area_id'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    $complaint_code = "CMP" . rand(1000, 9999);

    $complaint_date = date("Y-m-d H:i:s");
    $initial_response_deadline = date("Y-m-d H:i:s", strtotime("+7 hours"));
    $resolution_deadline = date("Y-m-d H:i:s", strtotime("+24 hours"));

    $repeated_flag = "No";

    $check_repeat = "SELECT * FROM complaints 
                     WHERE category_id='$category_id' 
                     AND area_id='$area_id'
                     AND complaint_date >= NOW() - INTERVAL 7 DAY";

    $repeat_result = mysqli_query($conn, $check_repeat);

    if (mysqli_num_rows($repeat_result) > 0) {
        $repeated_flag = "Yes";
    }

    $proof_file = "";

    if ($_FILES['proof_file']['name'] != "") {
        $allowed_types = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['proof_file']['name'];
        $file_size = $_FILES['proof_file']['size'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_types) && $file_size <= 2000000) {
            $filename = time() . "_" . rand(100, 999) . "." . $ext;
            $target = "../assets/uploads/complaint_proof/" . $filename;
            move_uploaded_file($_FILES['proof_file']['tmp_name'], $target);
            $proof_file = $filename;
        } else {
            $file_error = "Only JPG, JPEG, PNG files under 2MB are allowed.";
        }
    }

    if ($file_error == "") {
        if ($_POST['edit_id'] == "") {
            $insert = "INSERT INTO complaints
        (complaint_code,user_id,title,description,category_id,area_id,priority,complaint_date,proof_file,status,repeated_flag,initial_response_deadline,resolution_deadline)
        VALUES
        ('$complaint_code','$user_id','$title','$description','$category_id','$area_id','$priority','$complaint_date','$proof_file','Submitted','$repeated_flag','$initial_response_deadline','$resolution_deadline')";

            if (mysqli_query($conn, $insert)) {
                $last_id = mysqli_insert_id($conn);

                mysqli_query($conn, "INSERT INTO complaint_history(complaint_id,updated_by,old_status,new_status,remarks)
            VALUES('$last_id','$user_id','None','Submitted','Complaint Registered')");

                header("Location: register_complaint.php?msg=added&cid=" . $complaint_code);
                exit();
            }
        } else {
            $id = $_POST['edit_id'];

            mysqli_query($conn, "UPDATE complaints SET
        title='$title',
        description='$description',
        category_id='$category_id',
        area_id='$area_id',
        priority='$priority'
        WHERE complaint_id='$id'");

            header("Location: register_complaint.php?msg=updated");
            exit();
        }
    }
}
?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2>Register New Complaint</h2>

    <?php if ($success != "") { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        </script>
    <?php } ?>
    <?php if ($file_error != "") { ?>
        <div class="alert alert-danger"><?php echo $file_error; ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data" onsubmit="return validateComplaintForm();">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <div class="mb-3">
            <label>Complaint Title</label>
            <input type="text" name="title" id="title" class="form-control" required value="<?php echo $edit_title; ?>">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control" required><?php echo $edit_description; ?></textarea>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php
                $cat = mysqli_query($conn, "SELECT * FROM complaint_categories WHERE status='Active'");
                while ($c = mysqli_fetch_assoc($cat)) {
                    $selected = ($edit_category == $c['category_id']) ? "selected" : "";
                    echo "<option value='" . $c['category_id'] . "' $selected>" . $c['category_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Area / Spot</label>
            <select name="area_id" class="form-control" required>
                <option value="">Select Area</option>
                <?php
                $ar = mysqli_query($conn, "SELECT * FROM areas WHERE status='Active'");
                while ($a = mysqli_fetch_assoc($ar)) {
                    $selected = ($edit_area == $a['area_id']) ? "selected" : "";
                    echo "<option value='" . $a['area_id'] . "' $selected>" . $a['building_name'] . " - " . $a['area_name'] . " - " . $a['exact_spot'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Priority</label>
            <select name="priority" class="form-control" required>
                <option value="Low" <?php if ($edit_priority == "Low") echo "selected"; ?>>Low</option>
                <option value="Medium" <?php if ($edit_priority == "Medium") echo "selected"; ?>>Medium</option>
                <option value="High" <?php if ($edit_priority == "High") echo "selected"; ?>>High</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Upload Complaint Proof (JPG/JPEG/PNG only, Max 2MB)</label>
            <input type="file" name="proof_file" class="form-control">
        </div>

        <button type="submit" name="submit_complaint" class="btn btn-success">Submit Complaint</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>