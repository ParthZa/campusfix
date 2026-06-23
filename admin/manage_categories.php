<?php
include("../includes/auth.php");
include("../config/db.php");
/** @var mysqli $conn */

$edit_id = "";
$edit_name = "";
$edit_desc = "";

if(isset($_GET['edit']))
{
    $edit_id = $_GET['edit'];
    $edata = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM complaint_categories WHERE category_id='$edit_id'"));
    $edit_name = $edata['category_name'];
    $edit_desc = $edata['description'];
}

if(isset($_POST['save_category']))
{
    $name = $_POST['category_name'];
    $desc = $_POST['description'];

    if($_POST['edit_id'] == "")
    {
        mysqli_query($conn,"INSERT INTO complaint_categories(category_name,description,status) VALUES('$name','$desc','Active')");
    }
    else
    {
        $id = $_POST['edit_id'];
        mysqli_query($conn,"UPDATE complaint_categories SET category_name='$name', description='$desc' WHERE category_id='$id'");
    }

    header("Location: manage_categories.php");
    exit();
}

if(isset($_GET['toggle']))
{
    $id = $_GET['toggle'];
    $current = mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM complaint_categories WHERE category_id='$id'"));

    if($current['status'] == 'Active')
    {
        mysqli_query($conn,"UPDATE complaint_categories SET status='Disabled' WHERE category_id='$id'");
    }
    else
    {
        mysqli_query($conn,"UPDATE complaint_categories SET status='Active' WHERE category_id='$id'");
    }

    header("Location: manage_categories.php");
    exit();
}

include("../includes/header.php");
include("../includes/navbar.php");

$result = mysqli_query($conn,"SELECT * FROM complaint_categories");
?>

<div class="container mt-4">
    <h2>Manage Complaint Categories</h2>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <input type="text" name="category_name" class="form-control mb-2" placeholder="Category Name" required value="<?php echo $edit_name; ?>">
        <textarea name="description" class="form-control mb-2" placeholder="Description" required><?php echo $edit_desc; ?></textarea>
        <button type="submit" name="save_category" class="btn btn-success">
            <?php echo ($edit_id=="") ? "Add Category" : "Update Category"; ?>
        </button>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['category_id']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php echo $row['status']; ?></td>

            <td>
                <a href="?edit=<?php echo $row['category_id']; ?>" class="btn btn-info btn-sm">Edit</a>
            </td>

            <td>
                <?php
                if($row['status']=="Active")
                {
                    echo "<a href='?toggle=".$row['category_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-warning btn-sm'>Disable</a>";
                }
                else
                {
                    echo "<a href='?toggle=".$row['category_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-success btn-sm'>Activate</a>";
                }
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>