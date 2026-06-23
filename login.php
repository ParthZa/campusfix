<?php
session_start();
include("config/db.php");

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' AND status='Active'";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result)==1)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['role_id'] = $row['role_id'];

        if($row['role_id']==1)
        {
            header("Location: admin/admin_dashboard.php");
        }
        elseif($row['role_id']==2)
        {
            header("Location: staff/staff_dashboard.php");
        }
        else
        {
            header("Location: user/user_dashboard.php");
        }
    }
    else
    {
        $error = "Invalid Login Credentials";
    }
}
?>

<?php include("includes/header.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3>CampusFix Portal Login</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($error)){ ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>