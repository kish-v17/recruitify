<?php
include("sidebar.php");

// Pagination settings
$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Search settings
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

// Base queries with search condition
$jobseekerQuery = "SELECT User_Id, Image, CONCAT(Name) AS Full_Name, Email, Mobile, User_Type, Status 
                   FROM users_tbl 
                   WHERE User_Type = 'Jobseeker' 
                   AND (Name LIKE '%$search%' OR Email LIKE '%$search%' OR Mobile LIKE '%$search%') 
                   LIMIT $offset, $records_per_page";

$employerQuery = "SELECT User_Id, Image, CONCAT(Name) AS Full_Name, Email, Mobile, User_Type, Company_Id, Branch_Id 
                  FROM users_tbl 
                  WHERE User_Type = 'Employer' 
                  AND (Name LIKE '%$search%' OR Email LIKE '%$search%' OR Mobile LIKE '%$search%') 
                  LIMIT $offset, $records_per_page";

$jobseekerResult = mysqli_query($con, $jobseekerQuery);
$employerResult = mysqli_query($con, $employerQuery);

// Count total records for pagination
$totalJobseekers = mysqli_fetch_assoc(mysqli_query($con, 
    "SELECT COUNT(*) AS total 
     FROM users_tbl 
     WHERE User_Type = 'Jobseeker' 
     AND (Name LIKE '%$search%' OR Email LIKE '%$search%' OR Mobile LIKE '%$search%')"
));
$totalEmployers = mysqli_fetch_assoc(mysqli_query($con, 
    "SELECT COUNT(*) AS total 
     FROM users_tbl 
     WHERE User_Type = 'Employer' 
     AND (Name LIKE '%$search%' OR Email LIKE '%$search%' OR Mobile LIKE '%$search%')"
));
$totalPagesJobseekers = ceil($totalJobseekers['total'] / $records_per_page);
$totalPagesEmployers = ceil($totalEmployers['total'] / $records_per_page);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4 w-100">
                <div>
                    <h1>User Management</h1>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>

            <div class="card-body">
                <!-- Tabs for Jobseekers and Employers -->
                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="jobseeker-tab" data-bs-toggle="tab" href="#jobseekers" role="tab" aria-controls="jobseekers" aria-selected="true">Jobseekers</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="employer-tab" data-bs-toggle="tab" href="#employers" role="tab" aria-controls="employers" aria-selected="false">Employers</a>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="userTabsContent">
                    <!-- Jobseekers Tab -->
                    <div class="tab-pane fade show active" id="jobseekers" role="tabpanel" aria-labelledby="jobseeker-tab">
                        <h3>Jobseekers</h3>
                        <?php if (mysqli_num_rows($jobseekerResult)) { ?>
                            <table class="table border text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>User Image</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($user = mysqli_fetch_assoc($jobseekerResult)) { ?>
                                    <tr>
                                        <td><img src="../<?= $user['Image']; ?>" alt="<?= $user['Full_Name']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td><?= $user['Full_Name']; ?></td>
                                        <td><?= $user['Email']; ?></td>
                                        <td><?= $user['Mobile']; ?></td>
                                        <td>
                                            <a href="view-jobseeker.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-info btn-sm">View</a>
                                            <a href="update-jobseeker.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <?php if ($user['Status'] === 'Deleted') { ?>
                                                <a href="activate-user.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-success btn-sm">Activate</a>
                                            <?php } else { ?>
                                                <a href="delete-user.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                            <?php } ?>
                                            <a href="applications.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-secondary btn-sm">View Applications</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end">
                                    <?php for ($i = 1; $i <= $totalPagesJobseekers; $i++) { ?>
                                        <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </nav>
                        <?php } else { echo "No Jobseekers found!"; } ?>
                    </div>

                    <!-- Employers Tab -->
                    <div class="tab-pane fade" id="employers" role="tabpanel" aria-labelledby="employer-tab">
                        <h3>Employers</h3>
                        <?php if (mysqli_num_rows($employerResult)) { ?>
                            <table class="table border text-nowrap">
                                <thead class="table-light">
                                    <tr>
                                        <th>User Image</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Branch</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($user = mysqli_fetch_assoc($employerResult)) {
                                    $companyName = '';
                                    $branchAddress = '';

                                    if ($user['Company_Id']) {
                                        $companyRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT Name FROM company_tbl WHERE Company_Id = " . $user['Company_Id']));
                                        $companyName = $companyRow['Name'];
                                    }

                                    if ($user['Branch_Id']) {
                                        $branchRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT CONCAT(City, ', ', Country) as Address FROM branch_tbl WHERE Branch_Id = " . $user['Branch_Id']));
                                        $branchAddress = $branchRow['Address'];
                                    }
                                ?>
                                    <tr>
                                        <td><img src="../<?= $user['Image']; ?>" alt="<?= $user['Full_Name']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td><?= $user['Full_Name']; ?></td>
                                        <td><?= $user['Email']; ?></td>
                                        <td><?= $companyName; ?></td>
                                        <td><?= $branchAddress; ?></td>
                                        <td>
                                            <a href="view-employer.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-info btn-sm">View</a>
                                            <a href="update-employer.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="delete-user.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                            <a href="posts.php?user_id=<?= $user['User_Id']; ?>" class="btn btn-secondary btn-sm">Posts</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <nav aria-label="Page navigation ">
                                <ul class="pagination justify-content-end">
                                    <?php for ($i = 1; $i <= $totalPagesEmployers; $i++) { ?>
                                        <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>"><?= $i; ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </nav>
                        <?php } else { echo "No Employers found!"; } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php include("footer.php"); ?>
