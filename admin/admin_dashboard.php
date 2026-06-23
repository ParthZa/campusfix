<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");
/** @var mysqli $conn */


$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints"))['cnt'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status='Resolved'"))['cnt'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE status!='Resolved'"))['cnt'];
$repeated = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM complaints WHERE repeated_flag='Yes'"))['cnt'];
?>

<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['full_name']; ?></p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card p-3 shadow text-center">
                <h5>Total Complaints</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow text-center">
                <h5>Resolved</h5>
                <h2><?php echo $resolved; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow text-center">
                <h5>Pending</h5>
                <h2><?php echo $pending; ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-3 shadow text-center">
                <h5>Repeated</h5>
                <h2><?php echo $repeated; ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>Manage Categories</h4>
                <a href="manage_categories.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>Manage Areas</h4>
                <a href="manage_areas.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>View Complaints</h4>
                <a href="view_complaints.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>Manage Staff</h4>
                <a href="manage_staff.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>

        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>Area Report</h4>
                <a href="../reports/unresolved_by_area.php" class="btn btn-primary btn-sm">Open Report</a>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card p-3 shadow">
                <h4>Manage Users</h4>
                <a href="manage_users.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>