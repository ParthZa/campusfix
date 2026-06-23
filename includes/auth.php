<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: /campusfix/login.php");
    exit();
}

$current_file = $_SERVER['PHP_SELF'];

if(strpos($current_file, '/admin/') !== false && $_SESSION['role_id'] != 1)
{
    die("Access Denied");
}

if(strpos($current_file, '/staff/') !== false && $_SESSION['role_id'] != 2)
{
    die("Access Denied");
}

if(strpos($current_file, '/user/') !== false && $_SESSION['role_id'] != 3)
{
    die("Access Denied");
}
?>