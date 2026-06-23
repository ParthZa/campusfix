<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

$query = "SELECT areas.building_name, areas.area_name, areas.exact_spot, COUNT(complaints.complaint_id) AS total_pending
          FROM complaints
          JOIN areas ON complaints.area_id = areas.area_id
          WHERE complaints.status != 'Resolved'
          GROUP BY complaints.area_id
          ORDER BY total_pending DESC";

$result = mysqli_query($conn,$query);
?>

<div class="container mt-4">
    <h2>Unresolved Complaints By Area Report</h2>

    <table class="table table-bordered table-striped mt-3">
        <tr>
            <th>Building</th>
            <th>Area</th>
            <th>Exact Spot</th>
            <th>Total Unresolved Complaints</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['building_name']; ?></td>
            <td><?php echo $row['area_name']; ?></td>
            <td><?php echo $row['exact_spot']; ?></td>
            <td><?php echo $row['total_pending']; ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include("../includes/footer.php"); ?>