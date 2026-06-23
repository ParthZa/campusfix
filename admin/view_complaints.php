<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

/** @var mysqli $conn */

$query = "SELECT complaints.*, users.full_name, complaint_categories.category_name, areas.building_name, areas.area_name, areas.exact_spot,
          ca.file_path AS action_file,
          staffuser.full_name AS staff_name
          FROM complaints
          JOIN users ON complaints.user_id = users.user_id
          JOIN complaint_categories ON complaints.category_id = complaint_categories.category_id
          JOIN areas ON complaints.area_id = areas.area_id
          LEFT JOIN (
                SELECT complaint_id, MAX(attachment_id) as latest_attach
                FROM complaint_attachments
                GROUP BY complaint_id
          ) latest ON complaints.complaint_id = latest.complaint_id
          LEFT JOIN complaint_attachments ca ON latest.latest_attach = ca.attachment_id
          LEFT JOIN users AS staffuser ON complaints.assigned_staff = staffuser.user_id
          ORDER BY complaints.complaint_id DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4">
    <h2>All Registered Complaints</h2>

    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>Complaint ID</th>
            <th>User</th>
            <th>Title</th>
            <th>Priority</th>
            <th>Category</th>
            <th>Area</th>
            <th>Status</th>
            <th>Repeated</th>
            <th>Proof</th>
            <th>Action Proof</th>
            <th>SLA</th>
            <th>Assigned To</th>
            <th>Assign</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <?php
            $overdue = false;

            if ($row['status'] != 'Resolved' && strtotime(date("Y-m-d H:i:s")) > strtotime($row['resolution_deadline'])) {
                $overdue = true;
            }
            ?>
            <tr <?php if ($overdue) {
                    echo "style='background-color:#f8d7da;'";
                } ?>>
                <td><?php echo $row['complaint_code']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
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
                    if ($overdue) {
                        echo "<span class='badge bg-danger'>OVERDUE</span>";
                    } else {
                        echo "<span class='badge bg-success'>Within SLA</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['staff_name'] != "") {
                        echo $row['staff_name'];
                    } else {
                        echo "Not Assigned";
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['assigned_staff'] == "") {
                        echo '<a href="assign_complaint.php?id=' . $row['complaint_id'] . '" onclick="return confirm(\'Proceed to assign this complaint?\')" class="btn btn-sm btn-success">Assign</a>';
                    } else {
                        echo '<a href="assign_complaint.php?id=' . $row['complaint_id'] . '" onclick="return confirm(\'Proceed to assign this complaint?\')" class="btn btn-sm btn-warning">Reassign</a>';
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>