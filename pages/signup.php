<?php
// Start session and process form submission before any output
session_start();
require 'db_connect.php';

$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];

    if ($user_type === 'patient') {
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];

        try {
            $query = "INSERT INTO patients (full_name, username, password, dob, gender) VALUES (:full_name, :username, :password, :dob, :gender)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                'full_name' => $full_name,
                'username' => $username,
                'password' => $password,
                'dob' => $dob,
                'gender' => $gender
            ]);
            header("Location: signup.php?success=patient");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $full_name = $_POST['full_name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $nid_no = $_POST['nid_no'];
        $bmdc_reg_no = $_POST['bmdc_reg_no'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $query = "INSERT INTO physicians (full_name, gender, email, mobile, nid_no, bmdc_reg_no, username, password) 
                      VALUES (:full_name, :gender, :email, :mobile, :nid_no, :bmdc_reg_no, :username, :password)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                'full_name' => $full_name,
                'gender' => $gender,
                'email' => $email,
                'mobile' => $mobile,
                'nid_no' => $nid_no,
                'bmdc_reg_no' => $bmdc_reg_no,
                'username' => $username,
                'password' => $password
            ]);
            header("Location: signup.php?success=physician");
            exit();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - DengueCare</title>
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
        .signup-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 60px auto;
            max-width: 800px;
        }
        .signup-btn {
            background: #005bb5;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }
        .signup-btn:hover {
            background: #003087;
            transform: scale(1.05);
        }
        .toggle-btn {
            background: linear-gradient(90deg, #005bb5, #007bff);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 500;
            margin: 0 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .toggle-btn:hover {
            background: linear-gradient(90deg, #003087, #005bb5);
            transform: scale(1.1);
        }
        .toggle-btn.active {
            background: #003087;
        }
        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #b3c7ff;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #005bb5;
            box-shadow: 0 0 8px rgba(0, 91, 181, 0.2);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.5s ease-in-out;
        }
        .popup-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            animation: bounceIn 0.6s ease-in-out, pulse 2s infinite;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }
        .popup-content h2 {
            color: #003087;
            font-weight: 600;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .popup-content p {
            color: #333;
            font-size: 1rem;
            margin-bottom: 25px;
        }
        .popup-content .btn-close {
            background: #005bb5;
            color: #ffffff;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }
        .popup-content .btn-close:hover {
            background: #003087;
            transform: scale(1.05);
        }
        .popup-content .medical-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 10px;
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
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin: 10px 0;
            }
            .top-bar {
                display: none !important;
            }
            .popup-content {
                padding: 20px;
                max-width: 90%;
            }
            .popup-content .medical-icon {
                font-size: 2.5rem;
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
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a class="btn btn-outline-light py-2 px-3" href="login.php">
                        LOG IN
                        <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Signup Section -->
    <div class="container signup-container fade-in">
        <h2 class="text-center mb-4" style="color: #003087; font-weight: 700;">Sign Up for DengueCare</h2>
        <?php if ($error) { ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
        <div class="text-center mb-4">
            <button class="toggle-btn active" onclick="showForm('patient')">I am a Patient</button>
            <button class="toggle-btn" onclick="showForm('physician')">I am a Doctor/Physician</button>
        </div>

        <!-- Patient Signup Form -->
        <form id="patientForm" action="signup.php" method="POST" class="fade-in">
            <input type="hidden" name="user_type" value="patient">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="patientFullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="patientFullName" name="full_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="patientUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="patientUsername" name="username" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="patientPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="patientPassword" name="password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="patientDOB" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="patientDOB" name="dob" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="patientGender" class="form-label">Gender</label>
                <select class="form-select" id="patientGender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="signup-btn">Sign Up</button>
            </div>
            <div class="text-center mt-3">
                <a href="login.php" class="text-primary">Already have an account? Log In</a>
            </div>
        </form>

        <!-- Physician Signup Form -->
        <form id="physicianForm" action="signup.php" method="POST" class="fade-in" style="display: none;">
            <input type="hidden" name="user_type" value="physician">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="physicianFullName" class="form-label">Full Name (As Per NID)</label>
                    <input type="text" class="form-control" id="physicianFullName" name="full_name" placeholder="As Per NID" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="physicianGender" class="form-label">Gender</label>
                    <select class="form-select" id="physicianGender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="physicianEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="physicianEmail" name="email" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="physicianMobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="physicianMobile" name="mobile" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="physicianNID" class="form-label">NID No</label>
                    <input type="text" class="form-control" id="physicianNID" name="nid_no" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="physicianBMDC" class="form-label">BMDC Reg No</label>
                    <input type="text" class="form-control" id="physicianBMDC" name="bmdc_reg_no" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="physicianUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="physicianUsername" name="username" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="physicianPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="physicianPassword" name="password" required>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="signup-btn">Sign Up</button>
            </div>
            <div class="text-center mt-3">
                <a href="login.php" class="text-primary">Already have an account? Log In</a>
            </div>
        </form>
    </div>

    <!-- Success Popup -->
    <?php if ($success === 'patient') { ?>
        <div class="popup" id="successPopup">
            <div class="popup-content">
                <i class="fas fa-stethoscope medical-icon"></i>
                <h2>Congratulations!</h2>
                <p>Your registration as a patient was successful.</p>
                <a href="login.php" class="btn-close">Go to Login</a>
            </div>
        </div>
    <?php } elseif ($success === 'physician') { ?>
        <div class="popup" id="successPopup">
            <div class="popup-content">
                <i class="fas fa-stethoscope medical-icon"></i>
                <h2>Congratulations!</h2>
                <p>Registration successful. Your NID & BMDC Reg No match with the GOVT portal.</p>
                <a href="login.php" class="btn-close">Go to Login</a>
            </div>
        </div>
    <?php } ?>

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
    <!-- JSConfetti -->
    <script src="https://cdn.jsdelivr.net/npm/js-confetti@0.11.0/dist/js-confetti.browser.js"></script>
    <script>
        function showForm(type) {
            const patientForm = document.getElementById('patientForm');
            const physicianForm = document.getElementById('physicianForm');
            const patientBtn = document.querySelector('button[onclick="showForm(\'patient\')"]');
            const physicianBtn = document.querySelector('button[onclick="showForm(\'physician\')"]');

            if (type === 'patient') {
                patientForm.style.display = 'block';
                physicianForm.style.display = 'none';
                patientBtn.classList.add('active');
                physicianBtn.classList.remove('active');
            } else {
                patientForm.style.display = 'none';
                physicianForm.style.display = 'block';
                patientBtn.classList.remove('active');
                physicianBtn.classList.add('active');
            }
        }

        // Trigger healthcare-themed confetti animation for success popup
        if (document.getElementById('successPopup')) {
            const jsConfetti = new JSConfetti();
            jsConfetti.addConfetti({
                emojis: ['🩺', '💉', '🩹', '❤️'],
                confettiColors: ['#005bb5', '#007bff', '#ffffff', '#28a745'],
                confettiRadius: 6,
                confettiNumber: 250,
                duration: 2500
            });
        }
    </script>
</body>
</html>