<?php
// Hardcoded hospital data (5 hospitals in Dhaka with dengue treatment capabilities)
$hospitals = [
    [
        'name' => 'Dhaka Medical College Hospital',
        'address' => 'Secretariat Road, Dhaka 1000, Bangladesh',
        'contact' => '+880-2-55165088',
        'latitude' => 23.7256,
        'longitude' => 90.3987
    ],
    [
        'name' => 'Square Hospitals Ltd.',
        'address' => '18/F, Bir Uttam Qazi Nuruzzaman Sarak, Dhaka 1205, Bangladesh',
        'contact' => '+880-2-8144400',
        'latitude' => 23.7530,
        'longitude' => 90.3816
    ],
    [
        'name' => 'Bangabandhu Sheikh Mujib Medical University',
        'address' => 'Shahbag, Dhaka 1000, Bangladesh',
        'contact' => '+880-2-55165600',
        'latitude' => 23.7389,
        'longitude' => 90.3948
    ],
    [
        'name' => 'United Hospital Limited',
        'address' => 'Plot 15, Road 71, Gulshan, Dhaka 1212, Bangladesh',
        'contact' => '+880-2-8836000',
        'latitude' => 23.8046,
        'longitude' => 90.4156
    ],
    [
        'name' => 'Apollo Hospitals Dhaka',
        'address' => 'Plot 81, Block E, Bashundhara R/A, Dhaka 1229, Bangladesh',
        'contact' => '+880-2-55037242',
        'latitude' => 23.8119,
        'longitude' => 90.4305
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Locate Service - DengueCare</title>
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
            margin-bottom: 20px;
            text-align: center;
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
        .hospital-card {
            background: #ffffff;
            border: 1px solid #e6f0fa;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            padding: 15px;
        }
        .hospital-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .hospital-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #003087;
        }
        .hospital-card p {
            font-size: 0.95rem;
            color: #666;
            margin: 5px 0;
        }
        .button-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-directions, .btn-uber {
            flex: 1;
            min-width: 140px;
            border: none;
            border-radius: 25px;
            padding: 8px 15px;
            font-weight: 600;
            color: #ffffff;
            transition: background 0.3s, transform 0.2s;
            text-align: center;
        }
        .btn-directions {
            background: linear-gradient(90deg, #005bb5, #007bff);
        }
        .btn-directions:hover {
            background: linear-gradient(90deg, #003087, #005bb5);
            transform: scale(1.05);
        }
        .btn-uber {
            background: linear-gradient(90deg, #003087, #004aad);
        }
        .btn-uber:hover {
            background: linear-gradient(90deg, #002266, #003087);
            transform: scale(1.05);
        }
        .btn-uber.disabled {
            background: #6c757d;
            cursor: not-allowed;
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
            .hospital-card h5 {
                font-size: 1.1rem;
            }
            .hospital-card p {
                font-size: 0.9rem;
            }
            .button-container {
                flex-direction: column;
                gap: 8px;
            }
            .btn-directions, .btn-uber {
                min-width: 100%;
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
                    <a href="edu_content.php" class="nav-item nav-link">EDUCATION</a>
                    <a href="e_locate.php" class="nav-item nav-link active">EMERGENCY LOCATE</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a href="login.php" class="btn btn-outline-light py-2 px-3">
                        LOGIN
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
        <h2>Emergency Locate Service</h2>

        <!-- Hero Section -->
        <div class="hero-section">
            <h3>Find the Nearest Hospital for Critical Dengue</h3>
            <p>If you or a loved one is experiencing severe dengue symptoms such as severe abdominal pain, persistent vomiting, bleeding, or extreme fatigue, immediate medical attention is critical. Our Emergency Locate Service uses GPS to find the closest hospital equipped to treat dengue.</p>
            <button class="btn btn-locate" onclick="locateHospitals()">
                <i class="fas fa-map-marker-alt me-2"></i>Locate Nearest Hospital
            </button>
        </div>

        <!-- Alert Message -->
        <div id="alertMessage" class="alert alert-danger alert-message"></div>

        <!-- Hospital List -->
        <div id="hospitalList" class="row g-4"></div>

        <!-- How It Works Section -->
        <div class="how-it-works">
            <h4>How the Emergency Locate Service Works</h4>
            <ul>
                <li><strong>Recognize Critical Symptoms</strong>: Look for warning signs of severe dengue, such as severe abdominal pain, persistent vomiting, bleeding gums, blood in vomit or stool, or extreme tiredness. These require immediate hospital care.</li>
                <li><strong>Activate GPS Location</strong>: Click the "Locate Nearest Hospital" button to allow the browser to access your current location using GPS.</li>
                <li><strong>Find Nearest Hospital</strong>: Our system calculates the distance to hospitals with dengue treatment capabilities (e.g., ICU, blood bank) and displays the closest ones.</li>
                <li><strong>Get Directions or Book Uber</strong>: Click "Get Directions" to open Google Maps with navigation, or "Book Uber Ride" to request an Uber to the selected hospital.</li>
                <li><strong>Hospital Triage and Treatment</strong>: Upon arrival, hospital staff will perform triage to prioritize critical dengue cases. Treatment may include IV fluids, blood transfusions, and intensive monitoring.</li>
                <li><strong>Follow-Up Care</strong>: After stabilization, follow medical advice for recovery, including rest, hydration, and monitoring for complications.</li>
            </ul>
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
        // Hardcoded hospital data from PHP
        const hospitals = <?php echo json_encode($hospitals); ?>;
        let userLocation = null; // Store user's GPS coordinates

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

        // Function to locate hospitals
        function locateHospitals() {
            const alertMessage = document.getElementById('alertMessage');
            const hospitalList = document.getElementById('hospitalList');

            // Reset previous content
            alertMessage.style.display = 'none';
            hospitalList.innerHTML = '';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;
                        userLocation = { latitude: userLat, longitude: userLon }; // Store user location
                        console.log(`User Location: ${userLat}, ${userLon}`); // Debug log

                        // Calculate distances and add to hospitals array
                        hospitals.forEach(hospital => {
                            hospital.distance = calculateDistance(
                                userLat,
                                userLon,
                                hospital.latitude,
                                hospital.longitude
                            ).toFixed(2); // Round to 2 decimal places
                        });

                        // Sort hospitals by distance
                        hospitals.sort((a, b) => a.distance - b.distance);

                        // Render hospital cards
                        hospitals.forEach(hospital => {
                            const uberLink = userLocation
                                ? `https://m.uber.com/go/?pickup.latitude=${userLocation.latitude}&pickup.longitude=${userLocation.longitude}&dropoff.latitude=${hospital.latitude}&dropoff.longitude=${hospital.longitude}`
                                : '#';
                            console.log(`Uber Link for ${hospital.name}: ${uberLink}`); // Debug log
                            const card = `
                                <div class="col-lg-4 col-md-6">
                                    <div class="hospital-card">
                                        <h5>${hospital.name}</h5>
                                        <p><i class="fas fa-map-marker-alt me-2"></i>${hospital.address}</p>
                                        <p><i class="fas fa-phone-alt me-2"></i>${hospital.contact}</p>
                                        <p><i class="fas fa-ruler me-2"></i>Approx. ${hospital.distance} km away</p>
                                        <div class="button-container">
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=${hospital.latitude},${hospital.longitude}" target="_blank" class="btn btn-directions">
                                                <i class="fas fa-directions me-2"></i>Get Directions
                                            </a>
                                            <a href="${uberLink}" target="_blank" class="btn btn-uber ${!userLocation ? 'disabled' : ''}" ${!userLocation ? 'onclick="return false;"' : ''}>
                                                <i class="fab fa-uber me-2"></i>Book Uber Ride
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                            hospitalList.innerHTML += card;
                        });
                    },
                    (error) => {
                        // Handle GPS errors
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
                        userLocation = null; // Reset user location on error
                    }
                );
            } else {
                alertMessage.textContent = 'Geolocation is not supported by your browser.';
                alertMessage.style.display = 'block';
                userLocation = null; // Reset user location
            }
        }
    </script>
</body>
</html>