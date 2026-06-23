<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

$staff_id = $_SESSION['user_id'];

$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as cnt FROM complaints WHERE assigned_staff='$staff_id'"))['cnt'];
$resolved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as cnt FROM complaints WHERE assigned_staff='$staff_id' AND status='Resolved'"))['cnt'];
$pending = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as cnt FROM complaints WHERE assigned_staff='$staff_id' AND status!='Resolved'"))['cnt'];
?>

<div class="container mt-4">
    <h2>Staff Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['full_name']; ?></p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 shadow text-center">
                <h5>Assigned Complaints</h5>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow text-center">
                <h5>Resolved</h5>
                <h2><?php echo $resolved; ?></h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow text-center">
                <h5>Pending</h5>
                <h2><?php echo $pending; ?></h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4 mt-3">
            <div class="card p-3 shadow">
                <h4>My Assigned Complaints</h4>
                <a href="my_complaints.php" class="btn btn-primary btn-sm">Open</a>
            </div>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>