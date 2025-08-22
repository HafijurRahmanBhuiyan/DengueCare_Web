<?php
session_start();
require 'db_connect.php';

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit();
}

// Fetch hospitals (prioritize dengue-specialized)
try {
    $stmt = $conn->prepare("SELECT id, name, location, division, available_seats, dengue_specialized FROM hospitals WHERE available_seats > 0 ORDER BY dengue_specialized DESC, name");
    $stmt->execute();
    $hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Hospital fetch error: " . $e->getMessage());
    $error = "Error fetching hospital data. Please try again.";
}

// Handle hospital seat booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $hospital_id = $_POST['hospital_id'] ?? '';
    $seat_type = $_POST['seat_type'] ?? '';
    $patient_name = $_POST['patient_name'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $booking_date = $_POST['booking_date'] ?? '';

    // Validate inputs
    if (empty($hospital_id) || empty($seat_type) || empty($patient_name) || empty($contact_number) || empty($booking_date)) {
        $error = "All fields are required.";
    } else {
        try {
            // Check seat availability
            $stmt = $conn->prepare("SELECT available_seats FROM hospitals WHERE id = :hospital_id AND available_seats > 0");
            $stmt->execute(['hospital_id' => $hospital_id]);
            $hospital = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$hospital) {
                $error = "No seats available in the selected hospital.";
            } else {
                // Begin transaction
                $conn->beginTransaction();

                // Insert booking
                $stmt = $conn->prepare("
                    INSERT INTO hospital_bookings (patient_id, hospital_id, seat_type, booking_date, created_at)
                    VALUES (:patient_id, :hospital_id, :seat_type, :booking_date, NOW())
                ");
                $stmt->execute([
                    'patient_id' => $_SESSION['user_id'],
                    'hospital_id' => $hospital_id,
                    'seat_type' => $seat_type,
                    'booking_date' => $booking_date
                ]);

                // Update available seats
                $stmt = $conn->prepare("UPDATE hospitals SET available_seats = available_seats - 1 WHERE id = :hospital_id");
                $stmt->execute(['hospital_id' => $hospital_id]);

                $conn->commit();
                $success = "Hospital seat booked successfully!";
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Booking error: " . $e->getMessage());
            $error = "Error booking seat: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hospital Seat - DengueCare</title>
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
        .form-check-label {
            margin-left: 5px;
            cursor: pointer;
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
        .seat-type-card {
            background: #ffffff;
            border: 1px solid #e6f0fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .seat-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .seat-type-card input:checked + label {
            font-weight: 600;
            color: #005bb5;
        }
        .availability-info {
            background: #e6f0fa;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.95rem;
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
        }
        @media (max-width: 576px) {
            .top-bar {
                display: none !important;
            }
            .navbar-brand h1 {
                font-size: 1.8rem;
            }
            .seat-type-card {
                padding: 10px;
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
        <h2>Book a Hospital Seat</h2>

        <?php if (isset($success)) { ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php } ?>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <!-- Hospital Booking Form -->
        <div class="form-section">
            <h4><i class="fas fa-hospital"></i> Book a Seat for Dengue Treatment</h4>
            <form action="book_hospital.php" method="POST" class="row g-3" onsubmit="return validateForm()">
                <!-- Hospital Selection -->
                <div class="col-md-6">
                    <label for="hospital_id" class="form-label">Select Hospital</label>
                    <select class="form-select" id="hospital_id" name="hospital_id" required onchange="updateAvailability()">
                        <option value="">Choose a hospital</option>
                        <?php foreach ($hospitals as $hospital) { ?>
                            <option value="<?php echo $hospital['id']; ?>" data-seats="<?php echo $hospital['available_seats']; ?>">
                                <?php echo htmlspecialchars($hospital['name'] . ' (' . $hospital['location'] . ', ' . $hospital['division'] . ')') . ($hospital['dengue_specialized'] ? ' [Dengue Specialized]' : ''); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="availability-info" id="availability_info" style="display: none;">
                        Available Seats: <span id="available_seats">0</span>
                    </div>
                </div>
                <!-- Division Filter -->
                <div class="col-md-6">
                    <label for="division_filter" class="form-label">Filter by Division</label>
                    <select class="form-select" id="division_filter" onchange="filterHospitals()">
                        <option value="">All Divisions</option>
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
                <!-- Seat Type -->
                <div class="col-12">
                    <label class="form-label">Seat Type</label>
                    <div class="seat-type-card">
                        <input type="radio" id="general_ward" name="seat_type" value="General Ward" required>
                        <label for="general_ward">General Ward (Shared room, basic amenities)</label>
                    </div>
                    <div class="seat-type-card">
                        <input type="radio" id="private_cabin" name="seat_type" value="Private Cabin">
                        <label for="private_cabin">Private Cabin (Single room, enhanced comfort)</label>
                    </div>
                    <div class="seat-type-card">
                        <input type="radio" id="icu" name="seat_type" value="ICU">
                        <label for="icu">ICU (Intensive care for severe cases)</label>
                    </div>
                </div>
                <!-- Patient Details -->
                <div class="col-md-6">
                    <label for="patient_name" class="form-label">Patient Name</label>
                    <input type="text" class="form-control" id="patient_name" name="patient_name" required>
                </div>
                <div class="col-md-6">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="tel" class="form-control" id="contact_number" name="contact_number" required>
                </div>
                <!-- Booking Date -->
                <div class="col-md-6">
                    <label for="booking_date" class="form-label">Booking Date</label>
                    <input type="date" class="form-control" id="booking_date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-12 text-end">
                    <button type="submit" name="submit_booking" class="btn btn-primary">
                        <i class="fas fa-hospital-alt me-2"></i>Book Seat
                    </button>
                </div>
            </form>
        </div>

        <!-- Pop-up for Booking Confirmation -->
        <?php if (isset($success)) { ?>
            <div class="popup success show">
                <button type="button" class="btn-close float-end" onclick="closePopup()"></button>
                <h3>Booking Confirmed</h3>
                <p>Your seat will be booked. We will contact you later.</p>
            </div>
            <div class="popup-backdrop show"></div>
        <?php } ?>
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
        function validateForm() {
            const hospital = document.getElementById('hospital_id').value;
            const seatType = document.querySelector('input[name="seat_type"]:checked');
            const patientName = document.getElementById('patient_name').value;
            const contactNumber = document.getElementById('contact_number').value;
            const bookingDate = document.getElementById('booking_date').value;

            if (!hospital) {
                alert('Please select a hospital.');
                return false;
            }
            if (!seatType) {
                alert('Please select a seat type.');
                return false;
            }
            if (!patientName.trim()) {
                alert('Please enter the patient name.');
                return false;
            }
            if (!contactNumber.trim()) {
                alert('Please enter the contact number.');
                return false;
            }
            if (!bookingDate) {
                alert('Please select a booking date.');
                return false;
            }
            return true;
        }

        function updateAvailability() {
            const hospitalSelect = document.getElementById('hospital_id');
            const availabilityInfo = document.getElementById('availability_info');
            const availableSeats = document.getElementById('available_seats');
            const selectedOption = hospitalSelect.options[hospitalSelect.selectedIndex];

            if (selectedOption && selectedOption.value) {
                const seats = selectedOption.getAttribute('data-seats');
                availableSeats.textContent = seats;
                availabilityInfo.style.display = 'block';
            } else {
                availabilityInfo.style.display = 'none';
            }
        }

        function filterHospitals() {
            const divisionFilter = document.getElementById('division_filter').value;
            const hospitalSelect = document.getElementById('hospital_id');
            const options = hospitalSelect.options;

            for (let i = 1; i < options.length; i++) {
                const hospitalText = options[i].text;
                const show = !divisionFilter || hospitalText.includes(divisionFilter);
                options[i].style.display = show ? '' : 'none';
            }

            // Reset selection if filtered out
            if (hospitalSelect.value && options[hospitalSelect.selectedIndex].style.display === 'none') {
                hospitalSelect.value = '';
                updateAvailability();
            }
        }

        function closePopup() {
            document.querySelector('.popup').classList.remove('show');
            document.querySelector('.popup-backdrop').classList.remove('show');
        }
    </script>
</body>
</html>