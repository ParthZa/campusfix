<?php
include("../includes/auth.php");
include("../config/db.php");
/** @var mysqli $conn */

$edit_id = "";
$edit_building = "";
$edit_area = "";
$edit_spot = "";

if(isset($_GET['edit']))
{
    $edit_id = $_GET['edit'];
    $edata = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM areas WHERE area_id='$edit_id'"));
    $edit_building = $edata['building_name'];
    $edit_area = $edata['area_name'];
    $edit_spot = $edata['exact_spot'];
}

if(isset($_POST['save_area']))
{
    $building = $_POST['building_name'];
    $area = $_POST['area_name'];
    $spot = $_POST['exact_spot'];

    if($_POST['edit_id'] == "")
    {
        mysqli_query($conn,"INSERT INTO areas(building_name,area_name,exact_spot,status) VALUES('$building','$area','$spot','Active')");
    }
    else
    {
        $id = $_POST['edit_id'];
        mysqli_query($conn,"UPDATE areas SET building_name='$building', area_name='$area', exact_spot='$spot' WHERE area_id='$id'");
    }

    header("Location: manage_areas.php");
    exit();
}

if(isset($_GET['toggle']))
{
    $id = $_GET['toggle'];
    $current = mysqli_fetch_assoc(mysqli_query($conn,"SELECT status FROM areas WHERE area_id='$id'"));

    if($current['status'] == 'Active')
    {
        mysqli_query($conn,"UPDATE areas SET status='Disabled' WHERE area_id='$id'");
    }
    else
    {
        mysqli_query($conn,"UPDATE areas SET status='Active' WHERE area_id='$id'");
    }

    header("Location: manage_areas.php");
    exit();
}

include("../includes/header.php");
include("../includes/navbar.php");

$result = mysqli_query($conn,"SELECT * FROM areas");
?>

<div class="container mt-4">
    <h2>Manage Areas</h2>

    <form method="POST" class="mb-4">
        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
        <input type="text" name="building_name" class="form-control mb-2" placeholder="Building Name" required value="<?php echo $edit_building; ?>">
        <input type="text" name="area_name" class="form-control mb-2" placeholder="Area Name" required value="<?php echo $edit_area; ?>">
        <input type="text" name="exact_spot" class="form-control mb-2" placeholder="Exact Spot" required value="<?php echo $edit_spot; ?>">
        <button type="submit" name="save_area" class="btn btn-success">
            <?php echo ($edit_id=="") ? "Add Area" : "Update Area"; ?>
        </button>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Building</th>
            <th>Area</th>
            <th>Exact Spot</th>
            <th>Status</th>
            <th>Edit</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['area_id']; ?></td>
            <td><?php echo $row['building_name']; ?></td>
            <td><?php echo $row['area_name']; ?></td>
            <td><?php echo $row['exact_spot']; ?></td>
            <td><?php echo $row['status']; ?></td>

            <td><a href="?edit=<?php echo $row['area_id']; ?>" class="btn btn-info btn-sm">Edit</a></td>

            <td>
                <?php
                if($row['status']=="Active")
                {
                    echo "<a href='?toggle=".$row['area_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-warning btn-sm'>Disable</a>";
                }
                else
                {
                    echo "<a href='?toggle=".$row['area_id']."' onclick=\"return confirm('Are you sure to change status?')\" class='btn btn-success btn-sm'>Activate</a>";
                }
                ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>