<?php
include("../includes/auth.php");
include("../config/db.php");
/** @var mysqli $conn */

$complaint_id = $_GET['id'];

if(isset($_POST['assign_staff']))
{
    $staff_id = $_POST['staff_id'];
    $admin_id = $_SESSION['user_id'];

    mysqli_query($conn,"UPDATE complaints SET assigned_staff='$staff_id', status='Assigned' WHERE complaint_id='$complaint_id'");

    mysqli_query($conn,"INSERT INTO assignments(complaint_id,staff_id,assigned_by) VALUES('$complaint_id','$staff_id','$admin_id')");

    mysqli_query($conn,"INSERT INTO complaint_history(complaint_id,updated_by,old_status,new_status,remarks)
    VALUES('$complaint_id','$admin_id','Submitted','Assigned','Complaint Assigned To Staff')");

    header("Location: view_complaints.php");
}
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container mt-4">
    <h2>Assign Complaint To Staff</h2>

    <form method="POST">
        <div class="mb-3">
            <label>Select Staff</label>
            <select name="staff_id" class="form-control" required>
                <option value="">Choose Staff</option>
                <?php
                $staff = mysqli_query($conn,"SELECT * FROM users WHERE role_id=2");
                while($s = mysqli_fetch_assoc($staff))
                {
                    echo "<option value='".$s['user_id']."'>".$s['full_name']."</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" name="assign_staff" class="btn btn-primary">Assign Complaint</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>