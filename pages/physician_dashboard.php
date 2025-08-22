<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("Physician dashboard access denied: user_id=" . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unset') . ", session=" . print_r($_SESSION, true));
    echo "<pre>Access Denied. Session Data:\n";
    var_dump($_SESSION);
    echo "</pre>";
    echo "<p>Please <a href='login.php'>log in</a> as a physician.</p>";
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "denguecare");
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Fetch patients for Patient Management
$sql_patients = "SELECT id, full_name, username, dob, gender, created_at FROM patients";
$result_patients = $conn->query($sql_patients);
$patients = [];
if ($result_patients && $result_patients->num_rows > 0) {
    while ($row = $result_patients->fetch_assoc()) {
        $patients[] = $row;
    }
}

// Fetch schedules for Manage Schedule
$physician_id = $_SESSION['user_id'];
$sql_schedules = "SELECT time_slot, status, COUNT(*) as patient_count 
                  FROM schedules 
                  WHERE physician_id = ? 
                  GROUP BY time_slot, status 
                  ORDER BY time_slot";
$stmt = $conn->prepare($sql_schedules);
$stmt->bind_param("i", $physician_id);
$stmt->execute();
$result_schedules = $stmt->get_result();
$schedules = [];
if ($result_schedules && $result_schedules->num_rows > 0) {
    while ($row = $result_schedules->fetch_assoc()) {
        $schedules[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physician Dashboard - DengueCare</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #e6f0fa 0%, #ffffff 100%);
            margin: 0;
            color: #333;
        }
        .top-bar {
            background: linear-gradient(90deg, #003087, #005bb5);
            padding: 12px 0;
            font-size: 0.95rem;
            color: #e6f0fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .top-bar a {
            color: #e6f0fa;
            transition: color 0.3s;
        }
        .top-bar a:hover {
            color: #ffffff;
        }
        .navbar {
            background: linear-gradient(90deg, #005bb5, #007bff);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .navbar-brand h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .navbar-brand h1:hover {
            transform: scale(1.05);
        }
        .navbar-nav .nav-link {
            color: #e6f0fa !important;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }
        .btn-outline-light {
            border-color: #e6f0fa;
            color: #e6f0fa;
            font-weight: 500;
            transition: background 0.3s, color 0.3s;
        }
        .btn-outline-light:hover {
            background: #ffffff;
            color: #005bb5;
        }
        .dashboard-container {
            margin: 100px auto;
            max-width: 1200px;
            display: flex;
            min-height: calc(100vh - 300px);
        }
        .sidebar {
            width: 250px;
            background: #003087;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px 0 0 10px;
        }
        .sidebar .icon {
            font-size: 30px;
            color: #ffffff;
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }
        .sidebar h3 {
            margin: 0 0 20px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            color: #e6f0fa;
            text-decoration: none;
            font-size: 1.1rem;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar a:hover {
            background: #005bb5;
            color: #ffffff;
        }
        .sidebar a.active {
            background: #005bb5;
            color: #ffffff;
        }
        .content {
            flex: 1;
            background: #ffffff;
            padding: 30px;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .content h2 {
            color: #003087;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .patient-card, .schedule-card {
            background: #f8f9fa;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.5s ease-in;
            margin-bottom: 20px;
        }
        .patient-card:hover, .schedule-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .patient-card .card-img-top, .schedule-card .card-img-top {
            font-size: 80px;
            color: #005bb5;
            background: #e6f0fa;
            padding: 20px;
            text-align: center;
        }
        .patient-card .card-body, .schedule-card .card-body {
            padding: 20px;
        }
        .patient-card .card-title, .schedule-card .card-title {
            color: #003087;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .patient-card .card-text, .schedule-card .card-text {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 5px;
        }
        .nav-tabs .nav-link {
            color: #003087;
            font-weight: 500;
            border: none;
            border-bottom: 2px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #005bb5;
            border-bottom: 2px solid #005bb5;
            background: transparent;
        }
        .nav-tabs .nav-link:hover {
            color: #005bb5;
            border-bottom: 2px solid #005bb5;
        }
        .footer {
            background: linear-gradient(90deg, #003087, #005bb5);
            padding: 60px 0 20px;
            color: #e6f0fa;
        }
        .footer h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        .footer h5 {
            color: #e6f0fa;
            font-weight: 600;
            text-transform: uppercase;
        }
        .footer p, .footer a {
            color: #e6f0fa;
            transition: color 0.3s;
        }
        .footer a:hover {
            color: #ffffff;
        }
        .footer .btn-primary {
            background: #ffffff;
            color: #005bb5;
            border: none;
            transition: background 0.3s, color 0.3s;
        }
        .footer .btn-primary:hover {
            background: #e6f0fa;
            color: #003087;
        }
        .copyright {
            background: #002266;
            padding: 15px 0;
            font-size: 0.9rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
                margin: 80px 15px;
            }
            .sidebar {
                width: 100%;
                border-radius: 10px 10px 0 0;
            }
            .content {
                border-radius: 0 0 10px 10px;
            }
        }
        @media (max-width: 576px) {
            .top-bar {
                display: none !important;
            }
            .navbar-brand h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Start -->
    <div class="container-fluid fixed-top px-0">
        <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
            <div class="col-lg-6 px-5 text-start">
                <small><i class="fa fa-map-marker-alt me-2"></i>Basundhara R/A, Dhaka</small>
                <small class="ms-4"><i class="fa fa-envelope me-2"></i>support@denguecare.com</small>
            </div>
            <div class="col-lg-6 px-5 text-end">
                <small>Follow us:</small>
                <a class="ms-3" href="https://www.facebook.com/Feeerozzz"><i class="fab fa-facebook-f"></i></a>
                <a class="ms-3" href="https://www.twitter.com/Feeerozzz"><i class="fab fa-twitter"></i></a>
                <a class="ms-3" href="https://www.linkedin.com/Feroz.mahmud36"><i class="fab fa-linkedin-in"></i></a>
                <a class="ms-3" href="https://www.instagram.com/Feeerozzz"><i class="fab fa-instagram"></i></a>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-dark py-lg-0 px-lg-5">
            <a href="physician_dashboard.php" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw-bold m-0">DENGUE<span class="text-white">CARE</span></h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="physician_dashboard.php" class="nav-item nav-link">HOME</a>
                    <a href="hospital_management.php" class="nav-item nav-link">FIND HOSPITAL</a>
                    <a href="find_doctor.php" class="nav-item nav-link">FIND DOCTOR</a>
                    <a href="find_ambulance.php" class="nav-item nav-link">FIND AMBULANCE</a>
                    <a href="find_heatmap.php" class="nav-item nav-link">HEAT MAP</a>
                    
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a href="logout.php" class="btn btn-outline-light py-2 px-3">
                        LOGOUT
                        <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                            <i class="fa fa-sign-out-alt"></i>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <div class="sidebar">
            <i class="fas fa-user-md icon"></i>
            <h3>Physician Dashboard</h3>
            <ul>
                <li><a href="#patient-management" data-bs-toggle="tab" class="active">Patient Management</a></li>
                <li><a href="#manage-schedule" data-bs-toggle="tab">Manage Schedule</a></li>
                <li><a href="#keep-track">Keep Track</a></li>
                <li><a href="#medical-fees">Medical Fees</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="content">
            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Patient Management Tab -->
                <div class="tab-pane fade show active" id="patient-management">
                    <h2>Patient Management</h2>
                    <?php if (empty($patients)): ?>
                        <p>No patients found.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($patients as $patient): ?>
                                <div class="col-md-4">
                                    <div class="card patient-card">
                                        <i class="fas fa-user card-img-top"></i>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($patient['full_name']); ?></h5>
                                            <p class="card-text"><strong>Username:</strong> <?php echo htmlspecialchars($patient['username']); ?></p>
                                            <p class="card-text"><strong>DOB:</strong> <?php echo $patient['dob'] ? date('m/d/Y', strtotime($patient['dob'])) : 'N/A'; ?></p>
                                            <p class="card-text"><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></p>
                                            <p class="card-text"><strong>Created At:</strong> <?php echo date('m/d/Y H:i:s', strtotime($patient['created_at'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Manage Schedule Tab -->
                <div class="tab-pane fade" id="manage-schedule">
                    <h2>Manage Schedule</h2>
                    <?php if (empty($schedules)): ?>
                        <p>No schedules found.</p>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($schedules as $schedule): ?>
                                <div class="col-md-4">
                                    <div class="card schedule-card">
                                        <i class="fas fa-calendar-alt card-img-top"></i>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo date('m/d/Y H:i', strtotime($schedule['time_slot'])); ?></h5>
                                            <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($schedule['status']); ?></p>
                                            <p class="card-text"><strong>Patients Booked:</strong> <?php echo $schedule['patient_count']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <div class="container-fluid footer mt-5 pt-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h1 class="fw-bold mb-4">DENGUE<span class="text-white">CARE</span></h1>
                    <p>Stay Safe From Dengue & Be Safe!</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square me-1" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-square me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square me-0" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Address</h5>
                    <p><i class="fa fa-map-marker-alt me-3"></i>Basundhara R/A, Dhaka</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+8801536225340</p>
                    <p><i class="fa fa-envelope me-3"></i>support@denguecare.com</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Quick Links</h5>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact Us</a>
                    <a class="btn btn-link" href="">Our Services</a>
                    <a class="btn btn-link" href="">Support</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="mb-4">Need Update</h5>
                    <p>Add Your Mail & Get Notification</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                        <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        © <a href="#">DENGUE CARE BD</a>, All Right Reserved | 2025
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Designed By <a href="https://www.facebook.com/ferozmahmud.sheikh">FEROZ MAHMUD</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>