<?php
include("../config/db.php");
header('Content-Type: application/json');

$query = "SELECT complaints.complaint_code, complaints.title, complaints.status,
          areas.building_name, areas.area_name, areas.exact_spot
          FROM complaints
          JOIN areas ON complaints.area_id = areas.area_id
          WHERE complaints.status != 'Resolved'";

$result = mysqli_query($conn,$query);

$data = array();

while($row = mysqli_fetch_assoc($result))
{
    $data[] = $row;
}

echo json_encode($data);
?>