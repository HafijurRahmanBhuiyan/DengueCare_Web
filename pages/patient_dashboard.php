<?php
session_start();
require 'db_connect.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit();
}

// Handle dengue demographic form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_demographic'])) {
    try {
        $stmt = $conn->prepare("
            INSERT INTO dengue_demographic_data (
                patient_id, full_name, age, gender, nid_birth_cert, contact_number, email, blood_group,
                division, district, upazila, union_ward, residence_type,
                symptom_onset_date, diagnosis_date, dengue_type, hospitalized, hospital_name,
                travel_history, symptoms, previous_dengue, previous_dengue_year, previous_dengue_type,
                stagnant_water, mosquito_protection, outcome, deceased_date
            ) VALUES (
                :patient_id, :full_name, :age, :gender, :nid_birth_cert, :contact_number, :email, :blood_group,
                :division, :district, :upazila, :union_ward, :residence_type,
                :symptom_onset_date, :diagnosis_date, :dengue_type, :hospitalized, :hospital_name,
                :travel_history, :symptoms, :previous_dengue, :previous_dengue_year, :previous_dengue_type,
                :stagnant_water, :mosquito_protection, :outcome, :deceased_date
            )
        ");
        
        $symptoms = implode(', ', array_filter($_POST['symptoms'] ?? []));
        if (!empty($_POST['symptoms_other'])) {
            $symptoms .= ($symptoms ? ', ' : '') . $_POST['symptoms_other'];
        }
        
        $stmt->execute([
            'patient_id' => $_SESSION['user_id'],
            'full_name' => $_POST['full_name'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'nid_birth_cert' => $_POST['nid_birth_cert'] ?: null,
            'contact_number' => $_POST['contact_number'],
            'email' => $_POST['email'] ?: null,
            'blood_group' => $_POST['blood_group'],
            'division' => $_POST['division'],
            'district' => $_POST['district'],
            'upazila' => $_POST['upazila'],
            'union_ward' => $_POST['union_ward'],
            'residence_type' => $_POST['residence_type'],
            'symptom_onset_date' => !empty($_POST['symptom_onset_date']) ? $_POST['symptom_onset_date'] : null,
            'diagnosis_date' => !empty($_POST['diagnosis_date']) ? $_POST['diagnosis_date'] : null,
            'dengue_type' => $_POST['dengue_type'],
            'hospitalized' => $_POST['hospitalized'],
            'hospital_name' => $_POST['hospital_name'] ?: null,
            'travel_history' => $_POST['travel_history'] ?: null,
            'symptoms' => $symptoms,
            'previous_dengue' => $_POST['previous_dengue'],
            'previous_dengue_year' => $_POST['previous_dengue_year'] ?: null,
            'previous_dengue_type' => $_POST['previous_dengue_type'] ?: null,
            'stagnant_water' => $_POST['stagnant_water'],
            'mosquito_protection' => $_POST['mosquito_protection'],
            'outcome' => $_POST['outcome'],
            'deceased_date' => !empty($_POST['deceased_date']) ? $_POST['deceased_date'] : null
        ]);
        
        $submitted_data = $_POST;
        $submitted_data['symptoms'] = $symptoms;
        $success = "Demographic data submitted successfully! View your report below.";
    } catch (PDOException $e) {
        error_log("Demographic data error: " . $e->getMessage());
        $error = "Error submitting data. Please try again.";
    }
}

// Handle symptom and assessment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_symptoms'])) {
    try {
        // Calculate dengue likelihood
        $score = 0;
        
        // Q1: Fever Duration
        if ($_POST['fever_duration'] === '2+ days') {
            $score += 2;
        } elseif ($_POST['fever_duration'] === '<2 days') {
            $score += 1;
        }
        
        // Q2: Symptoms
        $symptoms = $_POST['symptoms'] ?? [];
        if (in_array('Fever', $symptoms)) $score += 1;
        if (in_array('Rash', $symptoms)) $score += 1;
        if (in_array('Joint Pain', $symptoms)) $score += 1;
        if (in_array('Bleeding', $symptoms)) $score += 2;
        
        // Q3: Travel History
        if (!empty(trim($_POST['travel_history']))) {
            $score += 1;
        }
        
        // Q4: Mosquito Exposure
        if ($_POST['mosquito_exposure'] === 'Yes') {
            $score += 1;
        }
        
        // Q5: Fatigue Level
        $fatigue = (int)$_POST['fatigue_level'];
        if ($fatigue == 5) {
            $score += 2;
        } elseif ($fatigue >= 3) {
            $score += 1;
        }
        
        // Determine result
        $result = $score >= 4 ? 'Positive' : 'Negative';
        
        // Save to database
        $stmt = $conn->prepare("
            INSERT INTO symptom_assessments (patient_id, name, dob, sex, result)
            VALUES (:patient_id, :name, :dob, :sex, :result)
        ");
        $stmt->execute([
            'patient_id' => $_SESSION['user_id'],
            'name' => $_POST['name'],
            'dob' => $_POST['dob'],
            'sex' => $_POST['sex'],
            'result' => $result
        ]);
        
        $assessment_result = $result;
        $success = "Assessment submitted successfully! Check the result below.";
    } catch (PDOException $e) {
        error_log("Symptom assessment error: " . $e->getMessage());
        $error = "Error submitting assessment. Please try again.";
    }
}

// Handle diagnosis report upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['diagnosis_report'])) {
    $upload_dir = 'uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['diagnosis_report'];
    $file_name = uniqid() . '_' . basename($file['name']);
    $file_path = $upload_dir . $file_name;
    
    if ($file['error'] === UPLOAD_ERR_OK && move_uploaded_file($file['tmp_name'], $file_path)) {
        try {
            $stmt = $conn->prepare("INSERT INTO reports (patient_id, file_path) VALUES (:patient_id, :file_path)");
            $stmt->execute([
                'patient_id' => $_SESSION['user_id'],
                'file_path' => $file_path
            ]);
            $success = "Diagnosis report uploaded successfully!";
        } catch (PDOException $e) {
            error_log("Report upload error: " . $e->getMessage());
            $error = "Error uploading report. Please try again.";
        }
    } else {
        $error = "Failed to upload report. Please try again.";
    }
}

// Handle physician appointment booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_appointment'])) {
    try {
        $stmt = $conn->prepare("
            INSERT INTO appointments (patient_id, physician_id, appointment_date)
            VALUES (:patient_id, :physician_id, :appointment_date)
        ");
        $stmt->execute([
            'patient_id' => $_SESSION['user_id'],
            'physician_id' => $_POST['physician_id'],
            'appointment_date' => $_POST['appointment_date']
        ]);
        
        $appointment_success = true;
        $success = "Appointment booked successfully! Our authority will contact you.";
    } catch (PDOException $e) {
        error_log("Appointment booking error: " . $e->getMessage());
        $error = "Error booking appointment. Please try again.";
    }
}

// Determine active section (default to demographic)
$active_section = isset($_GET['section']) ? $_GET['section'] : 'demographic';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - DengueCare</title>
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
            min-height: calc(100vh - 200px);
        }
        .sidebar {
            width: 250px;
            background: #ffffff;
            border-radius: 15px 0 0 15px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            color: #333;
            font-weight: 500;
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
        }
        .sidebar-item:hover {
            background: #e6f0fa;
            transform: translateX(5px);
        }
        .sidebar-item.active {
            background: #005bb5;
            color: #ffffff;
        }
        .sidebar-item i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        .premium-badge {
            background: #ffd700;
            color: #003087;
            font-size: 0.707rem;
            padding: 2px 6px;
            border-radius: 5px;
            margin-left: 5px;
            font-weight: 600;
        }
        .content-area {
            flex-grow: 1;
            background: #ffffff;
            border-radius: 0 15px 15px 0;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-area h2 {
            color: #003087;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #b3c7ff;
            padding: 12px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s, transform 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #005bb5;
            box-shadow: 0 0 8px rgba(0, 91, 181, 0.2);
            outline: none;
            transform: scale(1.02);
        }
        .form-check, .form-check-input {
            cursor: pointer;
        }
        .form-check-label {
            margin-left: 5px;
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
        .sticky-submit {
            position: sticky;
            bottom: 20px;
            text-align: right;
            padding: 15px 0;
            background: #ffffff;
            border-top: 1px solid #e6f0fa;
            z-index: 10;
        }
        .report-container {
            background: #ffffff;
            border: 2px solid #005bb5;
            border-radius: 15px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .report-container h3 {
            color: #003087;
            font-weight: 700;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }
        .report-container h4 {
            color: #005bb5;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 2px solid #e6f0fa;
            padding-bottom: 5px;
        }
        .report-container p {
            margin-bottom: 8px;
            font-size: 1rem;
        }
        .report-container ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 15px;
        }
        .report-container ul li {
            font-size: 1rem;
            margin-bottom: 5px;
        }
        .report-container ul li:before {
            content: "✔ ";
            color: #005bb5;
        }
        .report-branding {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-branding h1 {
            color: #003087;
            font-size: 2rem;
            font-weight: 700;
        }
        .report-branding p {
            color: #666;
            font-size: 0.9rem;
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            50% { transform: translateX(10px); }
            75% { transform: translateX(-10px); }
        }
        /* Pop-up Styling */
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
        .popup.positive {
            border-left: 5px solid #dc3545;
        }
        .popup.negative {
            border-left: 5px solid #28a745;
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
        /* Appointment Form Styling */
        .appointment-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        .appointment-card-header {
            background: linear-gradient(90deg, #003087, #005bb5);
            color: #ffffff;
            padding: 15px;
            border-radius: 15px 15px 0 0;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
        }
        .appointment-card-body {
            padding: 20px;
        }
        .input-group .input-group-text {
            background: #e6f0fa;
            border: 1px solid #b3c7ff;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #005bb5;
        }
        .input-group .form-control, .input-group .form-select {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }
        .form-label {
            font-weight: 500;
            color: #003087;
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
        @media print {
            body {
                background: #ffffff;
            }
            .sidebar, .navbar, .footer, .btn-primary, .alert, .sticky-submit, .form-section, .appointment-card {
                display: none !important;
            }
            .dashboard-container {
                margin: 0;
                display: block;
            }
            .content-area {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }
            .report-container {
                border: none;
                box-shadow: none;
                padding: 20px;
                margin: 0;
                max-width: 100%;
                width: 100%;
            }
            .report-container h3 {
                font-size: 1.6rem;
            }
            .report-container h4 {
                font-size: 1.2rem;
            }
            .report-container p, .report-container ul li {
                font-size: 0.95rem;
            }
        }
        @media (max-width: 992px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-radius: 15px 15px 0 0;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            .content-area {
                border-radius: 0 0 15px 15px;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            }
        }
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin: 10px 0;
            }
            .top-bar {
                display: none !important;
            }
            .dashboard-container {
                margin: 80px auto;
            }
            .content-area {
                padding: 20px;
            }
            .btn-primary {
                width: 100%;
            }
            .form-section, .appointment-card-body {
                padding: 15px;
            }
            .popup {
                width: 95%;
                padding: 20px;
            }
        }
        @media (max-width: 576px) {
            .content-area h2 {
                font-size: 1.5rem;
            }
            .sidebar-item {
                padding: 10px;
                font-size: 0.9rem;
            }
            .sidebar-item i {
                font-size: 1rem;
            }
            .report-container h3 {
                font-size: 1.4rem;
            }
            .appointment-card-header {
                font-size: 1.2rem;
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
            <a href="patient_dashboard.php" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw-bold m-0">DENGUE<span class="text-white">CARE</span></h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="patient_dashboard.php" class="nav-item nav-link">HOME</a>
                    <a href="hospital_management.php" class="nav-item nav-link">FIND HOSPITAL</a>
                    <a href="find_doctor.php" class="nav-item nav-link">FIND DOCTOR</a>
                    <a href="find_ambulance.php" class="nav-item nav-link">FIND AMBULANCE</a>
                    <a href="find_heatmap.php" class="nav-item nav-link">HEAT MAP</a>
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

    <!-- Dashboard Section -->
    <div class="container dashboard-container fade-in">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="?section=demographic" class="sidebar-item <?php echo $active_section === 'demographic' ? 'active' : ''; ?>">
                <i class="fas fa-user"></i> Dengue Demographic Data
            </a>
            <a href="?section=symptoms" class="sidebar-item <?php echo $active_section === 'symptoms' ? 'active' : ''; ?>">
                <i class="fas fa-thermometer"></i> Log Symptom & Assessment
            </a>
            <a href="?section=appointment" class="sidebar-item <?php echo $active_section === 'appointment' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> Book Physician Appointment
            </a>
            <a href="?section=heatmap" class="sidebar-item <?php echo $active_section === 'heatmap' ? 'active' : ''; ?>">
                <i class="fas fa-map-marked-alt"></i> Heatmap
            </a>
            <a href="?section=records" class="sidebar-item <?php echo $active_section === 'records' ? 'active' : ''; ?>">
                <i class="fas fa-file-medical"></i> Medical Records
            </a>
            <a href="?section=hospital" class="sidebar-item <?php echo $active_section === 'hospital' ? 'active' : ''; ?>">
                <i class="fas fa-hospital"></i> Book Hospital Seat
            </a>
            <a href="?section=education" class="sidebar-item <?php echo $active_section === 'education' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Educational Content
            </a>
            <a href="?section=emergency" class="sidebar-item <?php echo $active_section === 'emergency' ? 'active' : ''; ?>">
                <i class="fas fa-ambulance"></i> Emergency Locate Service
                <span class="premium-badge">Premium</span>
            </a>
            <a href="?section=blood" class="sidebar-item <?php echo $active_section === 'blood' ? 'active' : ''; ?>">
                <i class="fas fa-tint"></i> Blood Availability
                <span class="premium-badge">Premium</span>
            </a>
            <a href="logout.php" class="sidebar-item">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <?php if (isset($success)) { ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php } ?>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php } ?>

            <?php if ($active_section === 'demographic') { ?>
                <h2>Dengue Demographic Data</h2>
                <form action="patient_dashboard.php?section=demographic" method="POST" class="row g-3">
                    <!-- 1. Patient Information -->
                    <div class="form-section">
                        <h4><i class="fas fa-user"></i> 1. Patient Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="col-md-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age" min="0" required>
                            </div>
                            <div class="col-md-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="nid_birth_cert" class="form-label">NID/Birth Certificate Number</label>
                                <input type="text" class="form-control" id="nid_birth_cert" name="nid_birth_cert">
                            </div>
                            <div class="col-md-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email (optional)</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6">
                                <label for="blood_group" class="form-label">Blood Group</label>
                                <select class="form-select" id="blood_group" name="blood_group" required>
                                    <option value="">Select</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Address & Location -->
                    <div class="form-section">
                        <h4><i class="fas fa-map-marker-alt"></i> 2. Address & Location</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="division" class="form-label">Division</label>
                                <select class="form-select" id="division" name="division" required>
                                    <option value="">Select</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Chattogram">Chattogram</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" id="district" name="district" required>
                            </div>
                            <div class="col-md-6">
                                <label for="upazila" class="form-label">Upazila</label>
                                <input type="text" class="form-control" id="upazila" name="upazila" required>
                            </div>
                            <div class="col-md-6">
                                <label for="union_ward" class="form-label">Union/Ward</label>
                                <input type="text" class="form-control" id="union_ward" name="union_ward" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Current Residence Type</label>
                                <div>
                                    <input type="radio" id="urban" name="residence_type" value="Urban" required>
                                    <label for="urban">Urban</label>
                                    <input type="radio" id="rural" name="residence_type" value="Rural" class="ms-3">
                                    <label for="rural">Rural</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Clinical Information -->
                    <div class="form-section">
                        <h4><i class="fas fa-stethoscope"></i> 3. Clinical Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="symptom_onset_date" class="form-label">Date of Symptom Onset</label>
                                <input type="date" class="form-control" id="symptom_onset_date" name="symptom_onset_date">
                            </div>
                            <div class="col-md-6">
                                <label for="diagnosis_date" class="form-label">Date of Diagnosis</label>
                                <input type="date" class="form-control" id="diagnosis_date" name="diagnosis_date">
                            </div>
                            <div class="col-md-6">
                                <label for="dengue_type" class="form-label">Type of Dengue</label>
                                <select class="form-select" id="dengue_type" name="dengue_type" required>
                                    <option value="">Select</option>
                                    <option value="Dengue Fever">Dengue Fever</option>
                                    <option value="Dengue Hemorrhagic Fever">Dengue Hemorrhagic Fever (DHF)</option>
                                    <option value="Severe Dengue">Severe Dengue</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hospitalized?</label>
                                <div>
                                    <input type="radio" id="hospitalized_yes" name="hospitalized" value="Yes" required>
                                    <label for="hospitalized_yes">Yes</label>
                                    <input type="radio" id="hospitalized_no" name="hospitalized" value="No" class="ms-3">
                                    <label for="hospitalized_no">No</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="hospital_name" class="form-label">Hospital Name (if applicable)</label>
                                <input type="text" class="form-control" id="hospital_name" name="hospital_name">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Travel History -->
                    <div class="form-section">
                        <h4><i class="fas fa-plane"></i> 4. Travel History (Last 14 Days)</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <input type="radio" id="no_travel" name="travel_history" value="No Travel" onclick="toggleTravelInput(false)">
                                    <label for="no_travel">No Travel</label>
                                    <input type="radio" id="yes_travel" name="travel_history" value="" class="ms-3" onclick="toggleTravelInput(true)">
                                    <label for="yes_travel">Yes, traveled to:</label>
                                </div>
                                <input type="text" class="form-control mt-2" id="travel_history" name="travel_history" placeholder="Districts/Countries" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Symptoms -->
                    <div class="form-section">
                        <h4><i class="fas fa-thermometer"></i> 5. Symptoms Experienced</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_fever" name="symptoms[]" value="Fever">
                                    <label class="form-check-label" for="symptom_fever">Fever</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_headache" name="symptoms[]" value="Headache">
                                    <label class="form-check-label" for="symptom_headache">Headache</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_muscle_pain" name="symptoms[]" value="Muscle pain">
                                    <label class="form-check-label" for="symptom_muscle_pain">Muscle pain</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_joint_pain" name="symptoms[]" value="Joint pain">
                                    <label class="form-check-label" for="symptom_joint_pain">Joint pain</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_rash" name="symptoms[]" value="Rash">
                                    <label class="form-check-label" for="symptom_rash">Rash</label>
                                </div>
                               -reactive
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_bleeding" name="symptoms[]" value="Bleeding">
                                    <label class="form-check-label" for="symptom_bleeding">Bleeding</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_vomiting" name="symptoms[]" value="Vomiting">
                                    <label class="form-check-label" for="symptom_vomiting">Vomiting</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_abdominal_pain" name="symptoms[]" value="Abdominal pain">
                                    <label class="form-check-label" for="symptom_abdominal_pain">Abdominal pain</label>
                                </div>
                                <div class="form-check">
                                    <label for="symptoms_other" class="form-label">Others</label>
                                    <input type="text" class="form-control" id="symptoms_other" name="symptoms_other">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Previous Dengue History -->
                    <div class="form-section">
                        <h4><i class="fas fa-history"></i> 6. Previous Dengue History</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <input type="radio" id="prev_dengue_yes" name="previous_dengue" value="Yes" required onclick="togglePrevDengue(true)">
                                    <label for="prev_dengue_yes">Yes</label>
                                    <input type="radio" id="prev_dengue_no" name="previous_dengue" value="No" class="ms-3" onclick="togglePrevDengue(false)">
                                    <label for="prev_dengue_no">No</label>
                                    <input type="radio" id="prev_dengue_dont_know" name="previous_dengue" value="Dont Know" class="ms-3" onclick="togglePrevDengue(false)">
                                    <label for="prev_dengue_dont_know">Don’t Know</label>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label for="previous_dengue_year" class="form-label">Year</label>
                                        <input type="number" class="form-control" id="previous_dengue_year" name="previous_dengue_year" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="previous_dengue_type" class="form-label">Type</label>
                                        <input type="text" class="form-control" id="previous_dengue_type" name="previous_dengue_type" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7. Environmental Factors -->
                    <div class="form-section">
                        <h4><i class="fas fa-tree"></i> 7. Environmental Factors</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Live near stagnant water or construction sites?</label>
                                <div>
                                    <input type="radio" id="stagnant_water_yes" name="stagnant_water" value="Yes" required>
                                    <label for="stagnant_water_yes">Yes</label>
                                    <input type="radio" id="stagnant_water_no" name="stagnant_water" value="No" class="ms-3">
                                    <label for="stagnant_water_no">No</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Use mosquito nets or repellents?</label>
                                <div>
                                    <input type="radio" id="mosquito_protection_yes" name="mosquito_protection" value="Yes" required>
                                    <label for="mosquito_protection_yes">Yes</label>
                                    <input type="radio" id="mosquito_protection_no" name="mosquito_protection" value="No" class="ms-3">
                                    <label for="mosquito_protection_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 8. Outcome -->
                    <div class="form-section">
                        <h4><i class="fas fa-clipboard-check"></i> 8. Outcome</h4>
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <input type="radio" id="outcome_recovered" name="outcome" value="Recovered" required onclick="toggleDeceasedDate(false)">
                                    <label for="outcome_recovered">Recovered</label>
                                    <input type="radio" id="outcome_under_treatment" name="outcome" value="Under Treatment" class="ms-3" onclick="toggleDeceasedDate(false)">
                                    <label for="outcome_under_treatment">Under Treatment</label>
                                    <input type="radio" id="outcome_deceased" name="outcome" value="Deceased" class="ms-3" onclick="toggleDeceasedDate(true)">
                                    <label for="outcome_deceased">Deceased</label>
                                </div>
                                <div class="mt-2">
                                    <label for="deceased_date" class="form-label">Deceased Date (if applicable)</label>
                                    <input type="date" class="form-control" id="deceased_date" name="deceased_date" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sticky-submit">
                        <button type="submit" name="submit_demographic" class="btn btn-primary">Submit Data</button>
                    </div>
                </form>

                <!-- Printable Report -->
                <?php if (isset($submitted_data)) { ?>
                    <div class="report-container fade-in">
                        <div class="report-branding">
                            <h1>DENGUE<span style="color: #005bb5;">CARE</span></h1>
                            <p>📝 Dengue Demographic Data Report – Bangladesh</p>
                        </div>
                        <h4>1. Patient Information</h4>
                        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($submitted_data['full_name']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($submitted_data['age']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($submitted_data['gender']); ?></p>
                        <p><strong>NID/Birth Certificate Number:</strong> <?php echo htmlspecialchars($submitted_data['nid_birth_cert'] ?: 'N/A'); ?></p>
                        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($submitted_data['contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($submitted_data['email'] ?: 'N/A'); ?></p>
                        <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($submitted_data['blood_group']); ?></p>

                        <h4>2. Address & Location</h4>
                        <p><strong>Division:</strong> <?php echo htmlspecialchars($submitted_data['division']); ?></p>
                        <p><strong>District:</strong> <?php echo htmlspecialchars($submitted_data['district']); ?></p>
                        <p><strong>Upazila:</strong> <?php echo htmlspecialchars($submitted_data['upazila']); ?></p>
                        <p><strong>Union/Ward:</strong> <?php echo htmlspecialchars($submitted_data['union_ward']); ?></p>
                        <p><strong>Current Residence Type:</strong> <?php echo htmlspecialchars($submitted_data['residence_type']); ?></p>

                        <h4>3. Clinical Information</h4>
                        <p><strong>Date of Symptom Onset:</strong> <?php echo htmlspecialchars($submitted_data['symptom_onset_date'] ?: 'N/A'); ?></p>
                        <p><strong>Date of Diagnosis:</strong> <?php echo htmlspecialchars($submitted_data['diagnosis_date'] ?: 'N/A'); ?></p>
                        <p><strong>Type of Dengue:</strong> <?php echo htmlspecialchars($submitted_data['dengue_type']); ?></p>
                        <p><strong>Hospitalized:</strong> <?php echo htmlspecialchars($submitted_data['hospitalized']); ?></p>
                        <p><strong>Hospital Name:</strong> <?php echo htmlspecialchars($submitted_data['hospital_name'] ?: 'N/A'); ?></p>

                        <h4>4. Travel History (Last 14 Days)</h4>
                        <p><?php echo htmlspecialchars($submitted_data['travel_history'] ?: 'No Travel'); ?></p>

                        <h4>5. Symptoms Experienced</h4>
                        <ul>
                            <?php
                            $symptom_list = explode(', ', $submitted_data['symptoms']);
                            foreach ($symptom_list as $symptom) {
                                echo '<li>' . htmlspecialchars($symptom) . '</li>';
                            }
                            ?>
                        </ul>

                        <h4>6. Previous Dengue History</h4>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($submitted_data['previous_dengue']); ?></p>
                        <?php if ($submitted_data['previous_dengue'] === 'Yes') { ?>
                            <p><strong>Year:</strong> <?php echo htmlspecialchars($submitted_data['previous_dengue_year'] ?: 'N/A'); ?></p>
                            <p><strong>Type:</strong> <?php echo htmlspecialchars($submitted_data['previous_dengue_type'] ?: 'N/A'); ?></p>
                        <?php } ?>

                        <h4>7. Environmental Factors</h4>
                        <p><strong>Near stagnant water or construction sites?</strong> <?php echo htmlspecialchars($submitted_data['stagnant_water']); ?></p>
                        <p><strong>Use mosquito nets or repellents?</strong> <?php echo htmlspecialchars($submitted_data['mosquito_protection']); ?></p>

                        <h4>8. Outcome</h4>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($submitted_data['outcome']); ?></p>
                        <?php if ($submitted_data['outcome'] === 'Deceased') { ?>
                            <p><strong>Deceased Date:</strong> <?php echo htmlspecialchars($submitted_data['deceased_date'] ?: 'N/A'); ?></p>
                        <?php } ?>

                        <button onclick="window.print()" class="btn btn-primary mt-3">Print Report</button>
                    </div>
                <?php } ?>

            <?php } elseif ($active_section === 'symptoms') { ?>
                <h2>Log Symptom & Assessment</h2>
                <form action="patient_dashboard.php?section=symptoms" method="POST" class="row g-3">
                    <!-- Patient Information -->
                    <div class="form-section">
                        <h4><i class="fas fa-user"></i> Patient Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" required>
                            </div>
                            <div class="col-md-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select class="form-select" id="sex" name="sex" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Dengue Assessment -->
                    <div class="form-section">
                        <h4><i class="fas fa-stethoscope"></i> Dengue Assessment</h4>
                        <div class="row g-3">
                            <!-- Q1: Fever Duration -->
                            <div class="col-12">
                                <label class="form-label">1. How long have you had a fever?</label>
                                <div>
                                    <input type="radio" id="fever_none" name="fever_duration" value="None" required>
                                    <label for="fever_none">None</label>
                                    <input type="radio" id="fever_less_2" name="fever_duration" value="<2 days" class="ms-3">
                                    <label for="fever_less_2">Less than 2 days</label>
                                    <input type="radio" id="fever_2_plus" name="fever_duration" value="2+ days" class="ms-3">
                                    <label for="fever_2_plus">2 or more days</label>
                                </div>
                            </div>
                            <!-- Q2: Symptoms -->
                            <div class="col-12">
                                <label class="form-label">2. Which symptoms do you have? (Select all that apply)</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_fever" name="symptoms[]" value="Fever">
                                    <label class="form-check-label" for="symptom_fever">Fever</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_rash" name="symptoms[]" value="Rash">
                                    <label class="form-check-label" for="symptom_rash">Rash</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_joint_pain" name="symptoms[]" value="Joint Pain">
                                    <label class="form-check-label" for="symptom_joint_pain">Joint Pain</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="symptom_bleeding" name="symptoms[]" value="Bleeding">
                                    <label class="form-check-label" for="symptom_bleeding">Bleeding (e.g., nosebleed, gums)</label>
                                </div>
                            </div>
                            <!-- Q3: Travel History -->
                            <div class="col-12">
                                <label for="travel_history" class="form-label">3. Have you traveled to a dengue-prone area in the last 14 days? (e.g., Dhaka, Chattogram)</label>
                                <input type="text" class="form-control" id="travel_history" name="travel_history" placeholder="Enter locations or leave blank">
                            </div>
                            <!-- Q4: Mosquito Exposure -->
                            <div class="col-12">
                                <label class="form-label">4. Have you been bitten by mosquitoes recently?</label>
                                <div>
                                    <input type="radio" id="mosquito_yes" name="mosquito_exposure" value="Yes" required>
                                    <label for="mosquito_yes">Yes</label>
                                    <input type="radio" id="mosquito_no" name="mosquito_exposure" value="No" class="ms-3">
                                    <label for="mosquito_no">No</label>
                                </div>
                            </div>
                            <!-- Q5: Fatigue Level -->
                            <div class="col-12">
                                <label for="fatigue_level" class="form-label">5. Rate your fatigue level (1 = None, 5 = Severe)</label>
                                <input type="range" class="form-range" id="fatigue_level" name="fatigue_level" min="1" max="5" value="1" oninput="this.nextElementSibling.value = this.value">
                                <output>1</output>
                            </div>
                        </div>
                    </div>

                    <div class="sticky-submit">
                        <button type="submit" name="submit_symptoms" class="btn btn-primary">Submit Assessment</button>
                    </div>
                </form>

                <!-- Pop-up for Assessment Result -->
                <?php if (isset($assessment_result)) { ?>
                    <div class="popup <?php echo $assessment_result === 'Positive' ? 'positive' : 'negative'; ?> show">
                        <button type="button" class="btn-close float-end" onclick="closePopup()"></button>
                        <h3><?php echo $assessment_result === 'Positive' ? 'Dengue Positive' : 'Dengue Negative'; ?></h3>
                        <p>
                            <?php if ($assessment_result === 'Positive') { ?>
                                Your symptoms suggest a possible dengue infection. Please consult a doctor immediately and consider booking an appointment via our platform.
                            <?php } else { ?>
                                Your symptoms do not strongly indicate dengue. Continue monitoring your health and take preventive measures like using mosquito repellent.
                            <?php } ?>
                        </p>
                    </div>
                    <div class="popup-backdrop show"></div>
                <?php } ?>

                <hr>
                <h3>Upload Diagnosis Report</h3>
                <form action="patient_dashboard.php?section=symptoms" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="diagnosis_report" class="form-label">Select Report (PDF/Image)</label>
                        <input type="file" class="form-control" id="diagnosis_report" name="diagnosis_report" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Report</button>
                </form>

            <?php } elseif ($active_section === 'appointment') { ?>
                <h2>Book Physician Appointment</h2>
                <div class="appointment-card fade-in">
                    <div class="appointment-card-header">
                        <i class="fas fa-calendar-check me-2"></i> Schedule Your Appointment
                    </div>
                    <div class="appointment-card-body">
                        <form action="patient_dashboard.php?section=appointment" method="POST">
                            <div class="mb-3">
                                <label for="physician" class="form-label"><i class="fas fa-user-doctor me-2"></i>Select Physician</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-doctor"></i></span>
                                    <select class="form-select" id="physician" name="physician_id" required>
                                        <option value="">Choose a physician</option>
                                        <?php
                                        $physicians = $conn->query("SELECT id, username FROM physicians")->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($physicians as $physician) {
                                            echo "<option value='{$physician['id']}'>" . htmlspecialchars($physician['username']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label"><i class="fas fa-calendar-alt me-2"></i>Appointment Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" name="submit_appointment" class="btn btn-primary">Book Appointment</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Pop-up for Appointment Confirmation -->
                <?php if (isset($appointment_success) && $appointment_success) { ?>
                    <div class="popup success show">
                        <button type="button" class="btn-close float-end" onclick="closePopup()"></button>
                        <h3>Appointment Successful</h3>
                        <p>Appointment is successful. Our authority will contact you.</p>
                    </div>
                    <div class="popup-backdrop show"></div>
                <?php } ?>

            <?php } elseif ($active_section === 'heatmap') { ?>
                <h2>Dengue Heatmap</h2>
                <p>View dengue outbreak zones.</p>
                <a href="find_heatmap.php" class="btn btn-primary">Go to Heatmap</a>

            <?php } elseif ($active_section === 'records') { ?>
                <h2>Medical Records</h2>
                <p>Now , You Can Uploads Medical Records Here ! </p>
                <br>
                <a href="check_records.php" class="btn btn-primary">MEDICAL REPORTS</a>

                <!-- <?php
                $records = $conn->prepare("SELECT record_type, details, created_at FROM medical_records WHERE patient_id = :patient_id ORDER BY created_at DESC");
                $records->execute(['patient_id' => $_SESSION['user_id']]);
                $records = $records->fetchAll(PDO::FETCH_ASSOC);
                if ($records) {
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr><th>Type</th><th>Details</th><th>Date</th></tr></thead><tbody>';
                    foreach ($records as $record) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($record['record_type']) . '</td>';
                        echo '<td>' . htmlspecialchars($record['details']) . '</td>';
                        echo '<td>' . htmlspecialchars($record['created_at']) . '</td>';
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<p>No medical records found.</p>';
                }
                ?> -->

            <?php } elseif ($active_section === 'hospital') { ?>
                <h2>Book Hospital Seat</h2>
                <p>You Can Book a Seat For Your Dengue Paitents </p>
                <br>
                <a href="book_hospital.php" class="btn btn-primary">BOOK SEAT</a>
               

            <?php } elseif ($active_section === 'education') { ?>
                <h2>Educational Content</h2>
                <p>Click For More Educational Contents , Here !</p>
                <!-- <br> -->
                <a href="edu_content.php" class="btn btn-primary">MORE CONTENT</a>
                
                <!-- <ul>
                    <li><strong>Stay Hydrated</strong>: Drink plenty of fluids to avoid dehydration.</li>
                    <li><strong>Mosquito Protection</strong>: Use repellents and nets toprevent bites.</li>
                    <li><strong>Monitor Symptoms</strong>: Watch for fever, rash, or joint pain and consult a doctor.</li>
                </ul> -->

            <?php } elseif ($active_section === 'emergency') { ?>
                <h2>Emergency Locate Service <span class="premium-badge">Premium</span></h2>
                <p>Critical Dengue Paitent ? Try Our New Service !</p>
                <a href="e_locate.php" class="btn btn-primary">Find Emergency Services</a>

            <?php } elseif ($active_section === 'blood') { ?>
                <h2>Blood Availability <span class="premium-badge">Premium</span></h2>
                <p>Urgent Blood Bank Status.</p>
                <a href="blood.php" class="btn btn-primary">Check Blood Availability</a>

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
        function toggleTravelInput(enable) {
            const input = document.getElementById('travel_history');
            input.disabled = !enable;
            if (enable) {
                input.name = 'travel_history';
                input.value = '';
            } else {
                input.name = '';
                input.value = 'No Travel';
            }
        }

        function togglePrevDengue(enable) {
            const year = document.getElementById('previous_dengue_year');
            const type = document.getElementById('previous_dengue_type');
            year.disabled = !enable;
            type.disabled = !