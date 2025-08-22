<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'denguecare');
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Sorry, we're experiencing technical difficulties. Please try again later.");
}

// Fetch blood banks for blood stock and requests
$blood_banks = [];
$result = $conn->query("SELECT * FROM blood_banks");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $blood_banks[] = $row;
    }
} else {
    error_log("Failed to fetch blood banks: " . $conn->error);
}

// Fetch blood stock
$blood_stock = [];
$result = $conn->query("SELECT bb.name, bs.blood_group, bs.units_available 
                        FROM blood_stock bs 
                        JOIN blood_banks bb ON bs.hospital_id = bb.id 
                        ORDER BY bb.name, bs.blood_group");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $blood_stock[] = $row;
    }
} else {
    error_log("Failed to fetch blood stock: " . $conn->error);
}

// Handle blood request form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_blood'])) {
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $hospital_id = (int)$_POST['hospital_id'];
    $request_date = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO blood_requests (user_name, blood_group, hospital_id, request_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $user_name, $blood_group, $hospital_id, $request_date);
    if ($stmt->execute()) {
        $success_message = "Blood request submitted successfully!";
    } else {
        $error_message = "Failed to submit blood request. Please try again.";
        error_log("Blood request insertion failed: " . $stmt->error);
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Availability Service - DengueCare</title>
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
            max-width: 1200px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-container h2 {
            color: #003087;
            font-weight: 700;
            margin-bottom:ng: 30px;
        }
        .hero-section {
            background: linear-gradient(90deg, #005bb5, #007bff);
            color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
        }
        .hero-section h3 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        .hero-section p {
            font-size: 1.1rem;
            margin: 15px 0;
        }
        .btn-locate {
            background: #dc3545;
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: #ffffff;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-locate:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        .blood-bank-card {
            background: #ffffff;
            border: 1px solid #e6f0fa;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            padding: 15px;
        }
        .blood-bank-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .blood-bank-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #003087;
        }
        .blood-bank-card p {
            font-size: 0.95rem;
            color: #666;
            margin: 5px 0;
        }
        .btn-request {
            background: linear-gradient(90deg, #003087, #005bb5);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            color: #ffffff;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-request:hover {
            background: linear-gradient(90deg, #002266, #003087);
            transform: scale(1.05);
        }
        .blood-table {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        .blood-table h4 {
            color: #003087;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .table-responsive {
            border-radius: 10px;
            overflow-x: auto;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .table th {
            background: #005bb5;
            color: #ffffff;
        }
        .table tbody tr:hover {
            background: #e6f0fa;
        }
        .how-it-works {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
        }
        .how-it-works h4 {
            color: #003087;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .how-it-works ul {
            list-style-type: none;
            padding: 0;
        }
        .how-it-works li {
            font-size: 1rem;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            padding-left: 30px;
        }
        .how-it-works li:before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #005bb5;
            position: absolute;
            left: 0;
            top: 2px;
        }
        .alert-message {
            display: none;
            margin-bottom: 20px;
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
            .content-container {
                margin: 80px 15px;
                padding: 20px;
            }
            .hero-section h3 {
                font-size: 1.5rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
            .blood-bank-card h5 {
                font-size: 1.1rem;
            }
            .blood-bank-card p {
                font-size: 0.9rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
        }
        @media (max-width: 576px) {
            .top-bar {
                display: none !important;
            }
            .navbar-brand h1 {
                font-size: 1.8rem;
            }
            .btn-request {
                width: 100%;
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
                    <a href="logout.php" class="btn btn-outline-light py-2 px-3">
                        LOG OUT
                        <div class="d-inline-flex btn-sm-square bg-white text-primary rounded-circle ms-2">
                            <i class="fa fa-sign-in-alt"></i>
                        </div>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Content Section -->
    <div class="container content-container fade-in">
        <h2>Blood Availability Service</h2>

        <!-- Hero Section -->
        <div class="hero-section">
            <h3>Find and Request Blood for Dengue Treatment</h3>
            <p>In severe dengue cases, blood transfusions may be critical to replace lost blood components. Our Blood Availability Service helps you check blood stock, find the nearest blood bank, and request blood from blood banks in Dhaka.</p>
            <button class="btn btn-locate" onclick="locateBloodBanks()">
                <i class="fas fa-map-marker-alt me-2"></i>Locate Nearest Blood Bank
            </button>
            <button class="btn btn-request ms-3" data-bs-toggle="modal" data-bs-target="#requestBloodModal">
                <i class="fas fa-tint me-2"></i>Request Blood
            </button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger alert-message"></div>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Blood Bank List -->
        <div id="bloodBankList" class="row g-4"></div>

        <!-- Blood Availability Table -->
        <div class="blood-table">
            <h4>Blood Stock by Blood Bank</h4>
            <?php if (empty($blood_stock)): ?>
                <div class="alert alert-warning">No blood stock data available. Please contact support.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Blood Bank</th>
                                <th>A+</th>
                                <th>A-</th>
                                <th>B+</th>
                                <th>B-</th>
                                <th>AB+</th>
                                <th>AB-</th>
                                <th>O+</th>
                                <th>O-</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $current_bank = '';
                            foreach ($blood_stock as $stock) {
                                if ($stock['name'] !== $current_bank) {
                                    if ($current_bank !== '') {
                                        echo '</tr>';
                                    }
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($stock['name']) . '</td>';
                                    $current_bank = $stock['name'];
                                }
                                echo '<td>' . $stock['units_available'] . '</td>';
                            }
                            if ($current_bank !== '') {
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- How It Works Section -->
        <div class="how-it-works">
            <h4>How the Blood Availability Service Works</h4>
            <ul>
                <li><strong>Check Blood Availability</strong>: View the real-time stock of blood groups (A+, A-, B+, B-, AB+, AB-, O+, O-) across blood banks in Dhaka using the table above.</li>
                <li><strong>Locate Nearest Blood Bank</strong>: Click "Locate Nearest Blood Bank" to use GPS to find the closest blood bank with contact details and distance.</li>
                <li><strong>Request Blood</strong>: Use the "Request Blood" button to open a form, select your required blood group and blood bank, and submit your request.</li>
                <li><strong>Blood Bank Coordination</strong>: The blood bank will review your request, verify blood availability, and contact you to arrange the transfusion.</li>
                <li><strong>Emergency Transport</strong>: If needed, use the Emergency Locate Service to find transport to the blood bank.</li>
                <li><strong>Follow-Up</strong>: After receiving blood, follow medical advice for recovery, including monitoring for complications.</li>
            </ul>
        </div>
    </div>

    <!-- Request Blood Modal -->
    <div class="modal fade" id="requestBloodModal" tabindex="-1" aria-labelledby="requestBloodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestBloodModalLabel">Request Blood</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="bloodRequestForm">
                        <div class="mb-3">
                            <label for="user_name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="user_name" name="user_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-select" id="blood_group" name="blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hospital_id" class="form-label">Select Blood Bank</label>
                            <select class="form-select" id="hospital_id" name="hospital_id" required>
                                <option value="">Select Blood Bank</option>
                                <?php foreach ($blood_banks as $bank): ?>
                                    <option value="<?php echo $bank['id']; ?>"><?php echo htmlspecialchars($bank['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="request_blood" class="btn btn-request w-100">Submit Request</button>
                    </form>
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
    <script>
        // Hardcoded blood banks from PHP
        const bloodBanks = <?php echo json_encode($blood_banks); ?>;
        let userLocation = null;

        // Function to calculate distance using Haversine formula (in kilometers)
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        // Function to locate blood banks
        function locateBloodBanks() {
            const alertMessage = document.getElementById('alertMessage');
            const bloodBankList = document.getElementById('bloodBankList');

            // Reset previous content
            alertMessage.style.display = 'none';
            bloodBankList.innerHTML = '';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;
                        userLocation = { latitude: userLat, longitude: userLon };
                        console.log(`User Location: ${userLat}, ${userLon}`);

                        // Calculate distances and add to blood banks array
                        bloodBanks.forEach(bank => {
                            bank.distance = calculateDistance(
                                userLat,
                                userLon,
                                bank.latitude,
                                bank.longitude
                            ).toFixed(2);
                        });

                        // Sort blood banks by distance
                        bloodBanks.sort((a, b) => a.distance - b.distance);

                        // Render nearest blood bank card
                        const nearestBank = bloodBanks[0];
                        const card = `
                            <div class="col-12">
                                <div class="blood-bank-card">
                                    <h5>Nearest Blood Bank: ${nearestBank.name}</h5>
                                    <p><i class="fas fa-map-marker-alt me-2"></i>${nearestBank.address}</p>
                                    <p><i class="fas fa-phone-alt me-2"></i>${nearestBank.contact}</p>
                                    <p><i class="fas fa-ruler me-2"></i>Approx. ${nearestBank.distance} km away</p>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination=${nearestBank.latitude},${nearestBank.longitude}" target="_blank" class="btn btn-request">
                                        <i class="fas fa-directions me-2"></i>Get Directions
                                    </a>
                                </div>
                            </div>
                        `;
                        bloodBankList.innerHTML = card;
                    },
                    (error) => {
                        let errorMsg = 'Unable to retrieve your location. ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMsg += 'Please allow location access and try again.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMsg += 'Location information is unavailable.';
                                break;
                            case error.TIMEOUT:
                                errorMsg += 'The request to get location timed out.';
                                break;
                            default:
                                errorMsg += 'An unknown error occurred.';
                        }
                        alertMessage.textContent = errorMsg;
                        alertMessage.style.display = 'block';
                        userLocation = null;
                    }
                );
            } else {
                alertMessage.textContent = 'Geolocation is not supported by your browser.';
                alertMessage.style.display = 'block';
                userLocation = null;
            }
        }
    </script>
</body>
</html>