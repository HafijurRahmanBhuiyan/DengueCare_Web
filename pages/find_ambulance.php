<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Ambulance - DengueCare</title>
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
        /* Header Styles */
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
        /* Search Section */
        .search-section {
            background: #ffffff;
            padding: 50px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin: 40px 0;
            border: 1px solid #e0e7ff;
        }
        .search-section h2 {
            color: #003087;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 30px;
        }
        .form-control, .form-select {
            border-radius: 12px;
            border: 1px solid #b3c7ff;
            padding: 12px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #005bb5;
            box-shadow: 0 0 8px rgba(0, 91, 181, 0.2);
        }
        .btn-primary {
            background: #005bb5;
            border: none;
            border-radius: 25px;
            padding: 12px 35px;
            text-transform: uppercase;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #003087;
        }
        /* Ambulance Card */
        .ambulance-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .ambulance-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .ambulance-info h5 {
            margin: 0;
            color: #003087;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .ambulance-info p {
            margin: 6px 0;
            color: #444;
            font-size: 0.95rem;
        }
        .distance {
            font-size: 0.9rem;
            color: #666;
            font-style: italic;
        }
        .btn-book {
            background: #005bb5;
            color: #ffffff;
            padding: 10px 30px;
            border-radius: 25px;
            text-transform: uppercase;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s;
        }
        .btn-book:hover {
            background: #003087;
        }
        /* Footer Styles */
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
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .ambulance-card {
                flex-direction: column;
                text-align: center;
            }
            .search-section {
                padding: 30px 20px;
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
            <a href="../index.php" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw-bold m-0">DENGUE<span class="text-white">CARE</span></h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="../index.php" class="nav-item nav-link active">HOME</a>
                    <a href="hospital_management.php" class="nav-item nav-link">FIND HOSPITAL</a>
                    <a href="find_doctor.php" class="nav-item nav-link">FIND DOCTOR</a>
                    <a href="find_ambulance.php" class="nav-item nav-link">FIND AMBULANCE</a>
                    <a href="find_heatmap.php" class="nav-item nav-link">HEAT MAP</a>
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

    <!-- Find Ambulance Section -->
    <div class="container">
        <div class="search-section">
            <h2 class="text-center">
                <br> Find Your Ambulance</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by provider or type">
                </div>
                <div class="col-md-2">
                    <select id="typeFilter" class="form-select">
                        <option value="">All Types</option>
                        <option value="AC">AC Ambulance</option>
                        <option value="Non-AC">Non-AC Ambulance</option>
                        <option value="ICU">ICU Ambulance</option>
                        <option value="Freezing">Freezing Ambulance</option>
                        <option value="Neonatal">Neonatal Ambulance</option>
                        <option value="Air">Air Ambulance</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="cityFilter" class="form-select">
                        <option value="">All Cities</option>
                        <option value="Dhaka">Dhaka</option>
                        <option value="Chattogram">Chattogram</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Barisal">Barisal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="distanceFilter" class="form-select">
                        <option value="">All Distances</option>
                        <option value="5">Within 5 km</option>
                        <option value="10">Within 10 km</option>
                        <option value="25">Within 25 km</option>
                        <option value="50">Within 50 km</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" onclick="findNearestAmbulance()">Find Nearest Ambulance</button>
                </div>
            </div>
        </div>

        <!-- Ambulance List -->
        <div id="ambulanceList" class="row"></div>
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
        // Sample ambulance data (Bangladeshi context)
        const ambulances = [
            { type: "AC", provider: "Green Park Ambulance", city: "Dhaka", distance: 3.5, equipment: "Oxygen, Stretcher, BP Monitor", contact: "+8801712345678" },
            { type: "Non-AC", provider: "Sasthya Seba", city: "Dhaka", distance: 5.2, equipment: "Oxygen, Stretcher", contact: "+8801405600700" },
            { type: "ICU", provider: "LifeCare Ambulance", city: "Chattogram", distance: 12.8, equipment: "Ventilator, Defibrillator, Cardiac Monitor", contact: "+8801812345679" },
            { type: "Freezing", provider: "Seba Ambulance", city: "Sylhet", distance: 18.4, equipment: "Freezer Box (-7°C)", contact: "+8801786433932" },
            { type: "Neonatal", provider: "BabySafe Ambulance", city: "Dhaka", distance: 4.1, equipment: "Incubator, Oxygen, IV Therapy", contact: "+8801912345680" },
            { type: "Air", provider: "SkyMed Ambulance", city: "Dhaka", distance: 10.0, equipment: "ALS Equipment, Medical Flight Crew", contact: "+8801712345681" },
            { type: "AC", provider: "HealthRide Ambulance", city: "Rajshahi", distance: 15.6, equipment: "Oxygen, Stretcher, AC", contact: "+8801812345682" },
            { type: "Non-AC", provider: "QuickCare Ambulance", city: "Khulna", distance: 20.3, equipment: "Oxygen, Stretcher", contact: "+8801912345683" },
            { type: "ICU", provider: "MediTrans Ambulance", city: "Dhaka", distance: 6.7, equipment: "Ventilator, ECG Monitor, Defibrillator", contact: "+8801712345684" },
            { type: "Freezing", provider: "EternaCare Ambulance", city: "Chattogram", distance: 14.2, equipment: "Freezer Box (-5°C)", contact: "+8801812345685" },
            { type: "Neonatal", provider: "NeoLife Ambulance", city: "Sylhet", distance: 19.8, equipment: "Incubator, Monitoring Systems", contact: "+8801912345686" },
            { type: "Air", provider: "AeroMed Ambulance", city: "Dhaka", distance: 8.5, equipment: "ALS Equipment, Charter Aircraft", contact: "+8801712345687" },
            { type: "AC", provider: "ComfortCare Ambulance", city: "Barisal", distance: 22.1, equipment: "Oxygen, Stretcher, AC", contact: "+8801812345688" },
            { type: "Non-AC", provider: "RapidAid Ambulance", city: "Dhaka", distance: 4.9, equipment: "Oxygen, Stretcher", contact: "+8801912345689" },
            { type: "ICU", provider: "CriticalCare Ambulance", city: "Rajshahi", distance: 16.4, equipment: "Ventilator, Cardiac Monitor", contact: "+8801712345690" },
            { type: "Freezing", provider: "SafePassage Ambulance", city: "Dhaka", distance: 7.3, equipment: "Freezer Box (-7°C)", contact: "+8801812345691" },
            { type: "Neonatal", provider: "TinyCare Ambulance", city: "Chattogram", distance: 13.7, equipment: "Incubator, Oxygen", contact: "+8801912345692" },
            { type: "Air", provider: "FastFlight Ambulance", city: "Sylhet", distance: 21.2, equipment: "ALS Equipment, Medical Flight", contact: "+8801712345693" },
            { type: "AC", provider: "CityCare Ambulance", city: "Dhaka", distance: 5.8, equipment: "Oxygen, Stretcher, AC", contact: "+8801812345694" },
            { type: "Non-AC", provider: "EconoCare Ambulance", city: "Khulna", distance: 19.5, equipment: "Oxygen, Stretcher", contact: "+8801912345695" },
            { type: "ICU", provider: "EliteCare Ambulance", city: "Dhaka", distance: 6.2, equipment: "Ventilator, Defibrillator", contact: "+8801712345696" },
            { type: "Freezing", provider: "FrostCare Ambulance", city: "Barisal", distance: 23.0, equipment: "Freezer Box (-5°C)", contact: "+8801812345697" },
            { type: "Neonatal", provider: "InfantCare Ambulance", city: "Dhaka", distance: 4.4, equipment: "Incubator, IV Therapy", contact: "+8801912345698" },
            { type: "Air", provider: "SkyCare Ambulance", city: "Chattogram", distance: 15.1, equipment: "ALS Equipment, Charter Flight", contact: "+8801712345699" },
            { type: "AC", provider: "PrimeCare Ambulance", city: "Sylhet", distance: 17.9, equipment: "Oxygen, Stretcher, AC", contact: "+8801812345700" }
        ];

        // Render ambulance list
        function renderAmbulances(filteredAmbulances) {
            const ambulanceList = document.getElementById('ambulanceList');
            ambulanceList.innerHTML = '';
            filteredAmbulances.forEach(ambulance => {
                const ambulanceCard = document.createElement('div');
                ambulanceCard.className = 'col-md-6 col-lg-4';
                ambulanceCard.innerHTML = `
                    <div class="ambulance-card">
                        <div class="ambulance-info">
                            <h5>${ambulance.provider}</h5>
                            <p><strong>Type:</strong> ${ambulance.type}</p>
                            <p><strong>Equipment:</strong> ${ambulance.equipment}</p>
                            <p><strong>Location:</strong> ${ambulance.city}</p>
                            <p class="distance"><strong>Distance:</strong> ${ambulance.distance} km</p>
                            <p><strong>Contact:</strong> ${ambulance.contact}</p>
                            <a href="tel:${ambulance.contact}" class="btn-book">Book Now</a>
                        </div>
                    </div>
                `;
                ambulanceList.appendChild(ambulanceCard);
            });
        }

        // Filter ambulances
        function filterAmbulances() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const type = document.getElementById('typeFilter').value;
            const city = document.getElementById('cityFilter').value;
            const distance = document.getElementById('distanceFilter').value;

            const filteredAmbulances = ambulances.filter(ambulance => {
                return (
                    (search === '' || ambulance.provider.toLowerCase().includes(search) || ambulance.type.toLowerCase().includes(search)) &&
                    (type === '' || ambulance.type === type) &&
                    (city === '' || ambulance.city === city) &&
                    (distance === '' || ambulance.distance <= parseFloat(distance))
                );
            });

            renderAmbulances(filteredAmbulances);
        }

        // Find nearest ambulance using Geolocation API
        function findNearestAmbulance() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLat = position.coords.latitude;
                    const userLon = position.coords.longitude;

                    // Mock coordinates for Bangladeshi cities (approximate)
                    const cityCoords = {
                        "Dhaka": { lat: 23.8103, lon: 90.4125 },
                        "Chattogram": { lat: 22.3569, lon: 91.7832 },
                        "Sylhet": { lat: 24.8949, lon: 91.8687 },
                        "Rajshahi": { lat: 24.3745, lon: 88.6042 },
                        "Khulna": { lat: 22.8456, lon: 89.5403 },
                        "Barisal": { lat: 22.7010, lon: 90.3535 }
                    };

                    // Update distances based on user's location
                    ambulances.forEach(ambulance => {
                        const city = cityCoords[ambulance.city] || { lat: userLat, lon: userLon };
                        ambulance.distance = calculateDistance(userLat, userLon, city.lat, city.lon);
                    });

                    // Sort by distance
                    ambulances.sort((a, b) => a.distance - b.distance);

                    // Render updated list
                    filterAmbulances();
                }, error => {
                    alert('Unable to retrieve your location. Please enable location services and try again.');
                }, { enableHighAccuracy: true, timeout: 10000 });
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        // Haversine formula to calculate distance between two points
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return (R * c).toFixed(1); // Distance in km
        }

        // Event listeners for filters
        document.getElementById('searchInput').addEventListener('input', filterAmbulances);
        document.getElementById('typeFilter').addEventListener('change', filterAmbulances);
        document.getElementById('cityFilter').addEventListener('change', filterAmbulances);
        document.getElementById('distanceFilter').addEventListener('change', filterAmbulances);

        // Initial render with all ambulances
        renderAmbulances(ambulances);
    </script>
</body>
</html>