<?php
include("../includes/auth.php");
include("../config/db.php");

$edit_id = "";
$edit_name = "";
$edit_email = "";
$edit_password = "";
$edit_department = "";

if(isset($_GET['edit']))
{
    $edit_id = $_GET['edit'];
    $edata = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE user_id='$edit_id'"));
    $edit_name = $edata['full_name'];
    $edit_email = $edata['email'];
    $edit_password = $edata['password'];
    $edit_department = $edata['department'];
}

if(isset($_POST['save_user']))
{
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $department = $_POST['department'];

    if($_POST['edit_id']=="")
    {
        mysqli_query($conn,"INSERT INTO users(full_name,email,password,role_id,department,status) VALUES('$name','$email','$password',3,'$department','Active')");
    }
    else
    {
        $id = $_POST['edit_id'];
        mysqli_query($conn,"UPDATE users SET full_name='$name', email='$email', password='$password', department='$department' WHERE user_id='$id'");
    }

    header("Location: manage_users.php");
    exit();
}

if(isset($_GET['toggle']))
{
    $id = $_GET['toggle'];
    $current = mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM users WHERE user_id='$id'"));

    if($current['status']=="Active")
    {
        mysqli_query($conn,"UPDATE users SET status='Inactive' WHERE user_id='$id'");
    }
    else
    {
        mysqli_query($conn,"UPDATE users SET status='Active' WHERE user_id='$id'");
    }

    header("Location: manage_users.php");
    exit();
}

include("../includes/header.php");
include("../includes/navbar.php");

$result = mysqli_query($conn,"SELECT * FROM users WHERE role_id=3");
?>

<div class="container mt-4">
    <h2>Manage Student Users</h2>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <input type="text" name="full_name" class="form-control mb-2" placeholder="Full Name" required value="<?php echo $edit_name; ?>">
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required value="<?php echo $edit_email; ?>">
        <input type="text" name="password" class="form-control mb-2" placeholder="Password" required value="<?php echo $edit_password; ?>">
        <input type="text" name="department" class="form-control mb-2" placeholder="Department" required value="<?php echo $edit_department; ?>">
        <button type="submit" name="save_user" class="btn btn-success">
            <?php echo ($edit_id=="") ? "Add User" : "Update User"; ?>
        </button>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Department</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo "USR".str_pad($row['user_id'],3,"0",STR_PAD_LEFT); ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['status']; ?></td>

            <td><a href="?edit=<?php echo $row['user_id']; ?>" class="btn btn-info btn-sm">Edit</a></td>

            <td>
                <?php
                if($row['status']=="Active")
                {
                    echo "<a href='?toggle=".$row['user_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-warning btn-sm'>Deactivate</a>";
                }
                else
                {
                    echo "<a href='?toggle=".$row['user_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-success btn-sm'>Activate</a>";
                }
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>