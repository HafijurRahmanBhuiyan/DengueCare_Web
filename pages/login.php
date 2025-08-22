<?php
// Start session and process login before any output
session_start();
require 'db_connect.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_type = $_POST['user_type'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Log the login attempt for debugging
    error_log("Login attempt: username=$username, user_type=$user_type");

    try {
        // Query the appropriate table based on user_type
        if ($user_type === 'patient') {
            $query = "SELECT id, username, password FROM patients WHERE username = :username";
        } elseif ($user_type === 'physician') {
            $query = "SELECT id, username, password FROM physicians WHERE username = :username";
        } else {
            $error = "Invalid user type.";
            error_log("Invalid user_type: $user_type");
            $query = null;
        }

        if ($query) {
            $stmt = $conn->prepare($query);
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user_type;
                error_log("Login successful: user_id={$user['id']}, user_type=$user_type");
                header("Location: " . ($user_type === 'patient' ? 'patient_dashboard.php' : 'physician_dashboard.php'));
                exit();
            } else {
                $error = "Incorrect username or password.";
                error_log("Login failed: Incorrect username or password for username=$username");
            }
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - DengueCare</title>
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
        .login-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 60px auto;
            max-width: 600px;
            position: relative;
        }
        .login-btn {
            background: #005bb5;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
            width: 100%;
            max-width: 200px;
        }
        .login-btn:hover {
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
            min-width: 160px;
            text-align: center;
            cursor: pointer;
        }
        .toggle-btn:hover {
            background: linear-gradient(90deg, #003087, #005bb5);
            transform: scale(1.05);
        }
        .toggle-btn.active {
            background: #003087;
            transform: scale(1);
        }
        .form-control {
            border-radius: 12px;
            border: 1px solid #b3c7ff;
            padding: 12px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-control:focus {
            border-color: #005bb5;
            box-shadow: 0 0 8px rgba(0, 91, 181, 0.2);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-danger {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            animation: shake 0.3s ease-in-out;
            font-size: 1rem;
            text-align: center;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
        }
        .toggle-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        .form-container {
            transition: opacity 0.3s ease;
        }
        .form-container.hidden {
            display: none !important;
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
            .login-container {
                padding: 20px;
                margin: 30px auto;
                max-width: 90%;
            }
            .toggle-container {
                flex-direction: column;
                gap: 10px;
            }
            .toggle-btn {
                min-width: 100%;
                padding: 10px;
            }
            .login-btn {
                max-width: 100%;
            }
        }
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }
            .toggle-btn {
                font-size: 0.9rem;
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
                    <a class="btn btn-outline-light py-2 px-3" href="signup.php">
                        SIGN UP
                        <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                            <i class="fa fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Login Section -->
    <div class="container login-container fade-in">
        <h2 class="text-center mb-4" style="color: #003087; font-weight: 700;">Log In to DengueCare</h2>
        <?php if ($error) { ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
        <div class="toggle-container">
            <button class="toggle-btn active" onclick="showForm('patient')">I am a Patient</button>
            <button class="toggle-btn" onclick="showForm('physician')">I am a Doctor/Physician</button>
        </div>

        <!-- Patient Login Form -->
        <div id="patientForm" class="form-container fade-in">
            <form action="login.php" method="POST">
                <input type="hidden" name="user_type" value="patient">
                <div class="mb-3">
                    <label for="patientUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="patientUsername" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="patientPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="patientPassword" name="password" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="login-btn">Log In</button>
                </div>
                <div class="text-center mt-3">
                    <a href="signup.php" class="text-primary">Don't have an account? Sign Up</a>
                </div>
            </form>
        </div>

        <!-- Physician Login Form -->
        <div id="physicianForm" class="form-container fade-in hidden">
            <form action="login.php" method="POST">
                <input type="hidden" name="user_type" value="physician">
                <div class="mb-3">
                    <label for="physicianUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="physicianUsername" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="physicianPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="physicianPassword" name="password" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="login-btn">Log In</button>
                </div>
                <div class="text-center mt-3">
                    <a href="signup.php" class="text-primary">Don't have an account? Sign Up</a>
                </div>
            </form>
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
        function showForm(type) {
            console.log('Switching to form:', type); // Debug log
            const patientForm = document.getElementById('patientForm');
            const physicianForm = document.getElementById('physicianForm');
            const patientBtn = document.querySelector('button[onclick="showForm(\'patient\')"]');
            const physicianBtn = document.querySelector('button[onclick="showForm(\'physician\')"]');

            if (!patientForm || !physicianForm || !patientBtn || !physicianBtn) {
                console.error('Form or button elements not found');
                return;
            }

            if (type === 'patient') {
                patientForm.classList.remove('hidden');
                physicianForm.classList.add('hidden');
                patientBtn.classList.add('active');
                physicianBtn.classList.remove('active');
            } else if (type === 'physician') {
                patientForm.classList.add('hidden');
                physicianForm.classList.remove('hidden');
                patientBtn.classList.remove('active');
                physicianBtn.classList.add('active');
            } else {
                console.error('Invalid form type:', type);
            }
        }
    </script>
</body>
</html>