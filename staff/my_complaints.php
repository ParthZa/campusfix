<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

$staff_id = $_SESSION['user_id'];

$query = "SELECT complaints.*, complaint_categories.category_name, areas.building_name, areas.area_name, areas.exact_spot
          FROM complaints
          JOIN complaint_categories ON complaints.category_id = complaint_categories.category_id
          JOIN areas ON complaints.area_id = areas.area_id
          WHERE complaints.assigned_staff = '$staff_id'
          ORDER BY complaints.complaint_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>My Assigned Complaints</h2>

    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>Complaint ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Area</th>
            <th>Status</th>
            <th>User Proof</th>
            <th>Update</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['complaint_code']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['building_name'] . "/" . $row['area_name'] . "/" . $row['exact_spot']; ?></td>
                <td><?php echo $row['status']; ?></td>

                <td>
                    <?php
                    if ($row['proof_file'] != "") {
                        echo "<a href='../assets/uploads/complaint_proof/" . $row['proof_file'] . "' target='_blank' class='btn btn-sm btn-info'>View</a>";
                    } else {
                        echo "No File";
                    }
                    ?>
                </td>

                <td>
                    <a href="update_status.php?id=<?php echo $row['complaint_id']; ?>" class="btn btn-sm btn-success">Update Status</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>