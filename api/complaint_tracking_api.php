<?php
include("../config/db.php");
header('Content-Type: application/json');

if(isset($_GET['track_id']))
{
    $track_id = $_GET['track_id'];

    $query = "SELECT complaint_code,title,status,repeated_flag,resolution_deadline 
              FROM complaints 
              WHERE complaint_code='$track_id'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result)>0)
    {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    }
    else
    {
        echo json_encode(array("message"=>"Complaint Not Found"));
    }
}
else
{
    echo json_encode(array("message"=>"No Complaint ID Passed"));
}
?>