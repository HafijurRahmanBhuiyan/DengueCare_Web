<?php
session_start();
require 'db_connect.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit();
}

// Handle diagnosis report upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['diagnosis_report'])) {
    $upload_dir = 'Uploads/';
    $max_size = 5 * 1024 * 1024; // 5MB
    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];

    // Check server upload limits
    $upload_max = min(
        (int)(ini_get('upload_max_filesize') * 1024 * 1024),
        (int)(ini_get('post_max_size') * 1024 * 1024)
    );
    if ($upload_max < $max_size) {
        $error = "Server upload limit is too low (" . ($upload_max / (1024 * 1024)) . "MB). Contact administrator.";
    } else {
        // Ensure upload directory exists with correct permissions
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                $error = "Failed to create upload directory.";
                error_log("Failed to create directory: $upload_dir");
            }
        } elseif (!is_writable($upload_dir)) {
            $error = "Upload directory is not writable.";
            error_log("Directory not writable: $upload_dir");
        } else {
            $file = $_FILES['diagnosis_report'];
            // Validate file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = "File upload error (code: {$file['error']}).";
                error_log("File upload error: {$file['error']}");
            } elseif (!in_array($file['type'], $allowed_types)) {
                $error = "Invalid file type. Only PDF, JPG, or PNG allowed.";
            } elseif ($file['size'] > $max_size) {
                $error = "File too large. Max size is 5MB.";
            } else {
                $file_name = uniqid() . '_' . basename($file['name']);
                $file_path = $upload_dir . $file_name;

                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                    $error = "Failed to move uploaded file.";
                    error_log("Failed to move file to: $file_path");
                } else {
                    try {
                        $stmt = $conn->prepare("INSERT INTO reports (patient_id, file_path, created_at) VALUES (:patient_id, :file_path, NOW())");
                        $stmt->execute([
                            'patient_id' => $_SESSION['user_id'],
                            'file_path' => $file_path
                        ]);
                        $success = "Diagnosis report uploaded successfully!";
                    } catch (PDOException $e) {
                        $error = "Database error: " . $e->getMessage();
                        error_log("Report upload DB error: " . $e->getMessage());
                    }
                }
            }
        }
    }
}

// Fetch appointment data
try {
    $stmt = $conn->prepare("
        SELECT a.id, a.appointment_date, a.created_at, p.username AS physician_name
        FROM appointments a
        JOIN physicians p ON a.physician_id = p.id
        WHERE a.patient_id = :patient_id
        ORDER BY a.created_at DESC
    ");
    $stmt->execute(['patient_id' => $_SESSION['user_id']]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching appointments: " . $e->getMessage();
    error_log("Appointment fetch error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Records - DengueCare</title>
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
        .content-container {
            margin: 100px auto;
            max-width: 900px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-container h2 {
            color: #003087;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        .form-section h4 {
            color: #003087;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .form-section h4 i {
            margin-right: 10px;
            color: #005bb5;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #b3c7ff;
            padding: 12px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
        }
        .form-control:focus {
            border-color: #005bb5;
            box-shadow: 0 0 8px rgba(0, 91, 181, 0.2);
            outline: none;
            transform: scale(1.02);
        }
        .btn-primary {
            background: linear-gradient(90deg, #005bb5, #007bff);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #003087, #005bb5);
            transform: scale(1.05);
        }
        .table-container {
            margin-top: 30px;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .table thead {
            background: linear-gradient(90deg, #003087, #005bb5);
            color: #ffffff;
        }
        .table th, .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }
        .table tbody tr {
            transition: background 0.3s;
        }
        .table tbody tr:hover {
            background: #e6f0fa;
        }
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        .alert-success {
            animation: fadeIn 0.5s ease-in-out;
        }
        .alert-danger {
            animation: shake 0.3s ease-in-out;
        }
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            padding: 30px;
            max-width: 500px;
            width: 90%;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .popup.show {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }
        .popup.success {
            border-left: 5px solid #28a745;
        }
        .popup h3 {
            color: #003087;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .popup p {
            font-size: 1rem;
            color: #333;
        }
        .popup .btn-close {
            background: #e6f0fa;
            border-radius: 50%;
            padding: 5px;
            opacity: 1;
            transition: background 0.3s;
        }
        .popup .btn-close:hover {
            background: #005bb5;
        }
        .popup-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .popup-backdrop.show {
            opacity: 1;
            visibility: visible;
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
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
        }
        @media (max-width: 768px) {
            .content-container {
                margin: 80px 15px;
                padding: 20px;
            }
            .content-container h2 {
                font-size: 1.5rem;
            }
            .btn-primary {
                width: 100%;
            }
            .form-section {
                padding: 15px;
            }
            .popup {
                width: 95%;
                padding: 20px;
            }
            .table th, .table td {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
        @media (max-width: 576px) {
            .top-bar {
                display: none !important;
            }
            .navbar-brand h1 {
                font-size: 1.8rem;
            }
            .table th, .table td {
                font-size: 0.85rem;
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
            <a href="index.html" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw-bold m-0">DENGUE<span class="text-white">CARE</span></h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.html" class="nav-item nav-link">HOME</a>
                    <a href="about.html" class="nav-item nav-link">FIND HOSPITAL</a>
                    <a href="find_doctor.html" class="nav-item nav-link">FIND DOCTOR</a>
                    <a href="find_ambulance.html" class="nav-item nav-link">FIND AMBULANCE</a>
                    <a href="heatmap.html" class="nav-item nav-link">HEAT MAP</a>
                    <a href="patient_dashboard.php" class="nav-item nav-link">DASHBOARD</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <form action="logout.php" method="POST">
                        <button type="submit" class="btn btn-outline-light py-2 px-3">
                            LOGOUT
                            <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                                <i class="fa fa-sign-out-alt"></i>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Content Section -->
    <div class="container content-container fade-in">
        <h2>Check Your Records</h2>

        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <!-- Upload Diagnostic Report Form -->
        <div class="form-section">
            <h4><i class="fas fa-file-upload"></i> Upload Diagnostic Report</h4>
            <form action="check_records.php" method="POST" enctype="multipart/form-data" onsubmit="return validateFile()">
                <div class="mb-3">
                    <label for="diagnosis_report" class="form-label">Select Report (PDF, JPG, or PNG)</label>
                    <input type="file" class="form-control" id="diagnosis_report" name="diagnosis_report" accept=".pdf,.jpg,.jpeg,.png" required>
                    <small class="form-text text-muted">Max file size: 5MB. Allowed formats: PDF, JPG, PNG.</small>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Pop-up for Submission Confirmation -->
        <?php if (isset($success)) { ?>
            <div class="popup success show">
                <button type="button" class="btn-close float-end" onclick="closePopup()"></button>
                <h3>Report Submitted</h3>
                <p>Your diagnostic report has been successfully uploaded!</p>
            </div>
            <div class="popup-backdrop show"></div>
        <?php } ?>

        <!-- Appointment Records Table -->
        <div class="table-container">
            <h4><i class="fas fa-calendar-check"></i> Your Appointments</h4>
            <?php if (!empty($appointments)) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Physician Name</th>
                            <th>Appointment Date</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['physician_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['appointment_date']))); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($appointment['created_at']))); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No appointments found.</p>
            <?php } ?>
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
    <script>
        function validateFile() {
            const fileInput = document.getElementById('diagnosis_report');
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];

            if (!fileInput.files[0]) {
                alert('Please select a file.');
                return false;
            }

            const file = fileInput.files[0];
            if (!allowedTypes.includes(file.type)) {
                alert('Invalid file type. Only PDF, JPG, or PNG allowed.');
                return false;
            }
            if (file.size > maxSize) {
                alert('File too large. Max size is 5MB.');
                return false;
            }
            return true;
        }

        function closePopup() {
            document.querySelector('.popup').classList.remove('show');
            document.querySelector('.popup-backdrop').classList.remove('show');
        }
    </script>
</body>
</html>