<?php
include '../db-connect.php';
//error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php" style="margin-left:-30px">
                <img src="../images/logo.png" class="img-fluid logo-image">

                <div class="d-flex flex-column">
                    <strong class="logo-text">Recruitify</strong>
                    <small class="logo-slogan">Online Job Portal</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav" style="margin-left:-20px">
                <ul class="navbar-nav align-items-center ms-lg-5">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Homepage</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Recruitify</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                            <li><a class="dropdown-item" href="user-listings.php">Users</a></li>
                            <li><a class="dropdown-item" href="job-listings.php">Jobs</a></li>
                            <li><a class="dropdown-item" href="company-listings.php">Companies</a></li>
                            <li><a class="dropdown-item" href="job-app.php">Applications</a></li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>

                    <?php


                    if ($_SESSION['user_id']) {
                        $sql = "select U_Name,U_Image from User_tbl where U_Id='$_SESSION[user_id]'";
                        $data = mysqli_query($con, $sql);
                        $result = mysqli_fetch_array($data);
                        $_SESSION["username"] = $result['U_Name'];
                        $img = $result['U_Image'];
                        if (isset($_SESSION['username'])) {
                            $use = $_SESSION['username'];
                        }

                        echo '
                             <li class="nav-item ms-lg-auto dropdown" style="margin-right:-50px">
                                <a role="button" data-bs-toggle="dropdown" aria-expanded="false">' . $use . '</a>
                               &emsp;
                                <a class="nav-link custom-btn btn" href="../logout.php">Log out</a>
                        </ul>
                        </li>';
                    } else {
                        echo ' <li class="nav-item ms-lg-auto">
                            <a class="nav-link" href="../register.php">Register</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link custom-btn btn" href="../login.php">Login</a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>