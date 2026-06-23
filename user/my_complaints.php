<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

$user_id = $_SESSION['user_id'];

$query = "SELECT complaints.*, complaint_categories.category_name, areas.building_name, areas.area_name, areas.exact_spot,
          ca.file_path AS action_file
          FROM complaints
          JOIN complaint_categories ON complaints.category_id = complaint_categories.category_id
          JOIN areas ON complaints.area_id = areas.area_id
          LEFT JOIN (
                SELECT complaint_id, MAX(attachment_id) as latest_attach
                FROM complaint_attachments
                GROUP BY complaint_id
          ) latest ON complaints.complaint_id = latest.complaint_id
          LEFT JOIN complaint_attachments ca ON latest.latest_attach = ca.attachment_id
          WHERE complaints.user_id = '$user_id'
          ORDER BY complaints.complaint_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>My Complaints</h2>

    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>Complaint ID</th>
            <th>Title</th>
            <th>Priority</th>
            <th>Category</th>
            <th>Area</th>
            <th>Status</th>
            <th>Repeated</th>
            <th>Proof</th>
            <th>Action Proof</th>
            <th>Edit</th>
            <th>SLA Deadline</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['complaint_code']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td>
                    <?php
                    if ($row['priority'] == "High") {
                        echo "<span class='badge bg-danger'>High</span>";
                    } elseif ($row['priority'] == "Medium") {
                        echo "<span class='badge bg-warning text-dark'>Medium</span>";
                    } else {
                        echo "<span class='badge bg-success'>Low</span>";
                    }
                    ?>
                </td>
                <td><?php echo $row['category_name']; ?></td>
                <td><?php echo $row['building_name'] . "/" . $row['area_name'] . "/" . $row['exact_spot']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['repeated_flag']; ?></td>
                <td>
                    <?php
                    if ($row['proof_file'] != "") {
                        echo "<a href='../assets/uploads/complaint_proof/" . $row['proof_file'] . "' target='_blank' class='btn btn-sm btn-info'>View Proof</a>";
                    } else {
                        echo "No File";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['action_file'] != "") {
                        echo "<a href='../assets/uploads/action_proof/" . $row['action_file'] . "' target='_blank' class='btn btn-sm btn-warning'>View Action</a>";
                    } else {
                        echo "Pending";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['status'] == "Submitted") {
                        echo '<a href="register_complaint.php?edit=' . $row['complaint_id'] . '" class="btn btn-info btn-sm">Edit</a>';
                    } else {
                        echo "Locked";
                    }
                    ?>
                </td>
                <td><?php echo $row['resolution_deadline']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>