<?php
include("../includes/auth.php");
include("../config/db.php");
include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container mt-4">
    <h2>Track Complaint Status (AJAX Live)</h2>

    <div class="row">
        <div class="col-md-6">
            <input type="text" id="track_id" class="form-control" placeholder="Enter Complaint ID">
        </div>
        <div class="col-md-2">
            <button onclick="trackComplaint()" class="btn btn-primary">Track</button>
        </div>
    </div>

    <hr>

    <div id="tracking_result"></div>
</div>

<?php include("../includes/footer.php"); ?>