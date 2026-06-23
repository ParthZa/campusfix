<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">CampusFix Portal</a>

        <div class="d-flex gap-2">

            <?php
            if($_SESSION['role_id']==1)
            {
                echo '<a href="/campusfix/admin/admin_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>';
            }
            elseif($_SESSION['role_id']==2)
            {
                echo '<a href="/campusfix/staff/staff_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>';
            }
            else
            {
                echo '<a href="/campusfix/user/user_dashboard.php" class="btn btn-light btn-sm">Dashboard</a>';
            }
            ?>

            <button onclick="toggleTheme()" class="btn btn-warning btn-sm">Toggle Theme</button>
            <a href="/campusfix/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>