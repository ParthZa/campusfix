<?php
include("../config/db.php");

if(isset($_POST['track_id']))
{
    $track_id = $_POST['track_id'];

    $find = mysqli_query($conn,"SELECT complaint_id,title,status,repeated_flag FROM complaints WHERE complaint_code='$track_id'");

    if(mysqli_num_rows($find)>0)
    {
        $comp = mysqli_fetch_assoc($find);

        echo "<h4>Complaint Title: ".$comp['title']."</h4>";
        echo "<p><strong>Current Status:</strong> ".$comp['status']."</p>";
        echo "<p><strong>Repeated Complaint:</strong> ".$comp['repeated_flag']."</p>";

        $cid = $comp['complaint_id'];

        $history = mysqli_query($conn,"SELECT complaint_history.*, users.full_name 
                                       FROM complaint_history
                                       JOIN users ON complaint_history.updated_by = users.user_id
                                       WHERE complaint_history.complaint_id='$cid'
                                       ORDER BY complaint_history.history_id ASC");

        while($h = mysqli_fetch_assoc($history))
        {
            echo "<div class='card mb-3 p-3 shadow-sm'>";
            echo "<h5>".$h['new_status']."</h5>";
            echo "<p><strong>Updated By:</strong> ".$h['full_name']."</p>";
            echo "<p><strong>Remarks:</strong> ".$h['remarks']."</p>";
            echo "<p><strong>Time:</strong> ".$h['updated_at']."</p>";
            echo "</div>";
        }
    }
    else
    {
        echo "<div class='alert alert-danger'>Complaint ID Not Found</div>";
    }
}
?>