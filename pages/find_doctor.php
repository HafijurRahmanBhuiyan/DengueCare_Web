<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENGUE CARE : FIND DOCTOR </title>
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
        /* Doctor Card */
        .doctor-card {
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
        .doctor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .doctor-card img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin-right: 25px;
            border: 4px solid #b3c7ff;
            object-fit: cover;
        }
        .doctor-info h5 {
            margin: 0;
            color: #003087;
            font-weight: 600;
            font-size: 1.5rem;
        }
        .doctor-info p {
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
            .doctor-card {
                flex-direction: column;
                text-align: center;
            }
            .doctor-card img {
                margin: 0 auto 20px;
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

    <!-- Find Doctor Section -->
    <div class="container">
        <div class="search-section">
            <h2 class="text-center">
                <br> Find Your Doctor</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by name or specialty">
                </div>
                <div class="col-md-2">
                    <select id="genderFilter" class="form-select">
                        <option value="">All Genders</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="specialtyFilter" class="form-select">
                        <option value="">All Specialties</option>
                        <option value="Cardiologist">Cardiologist</option>
                        <option value="Dermatologist">Dermatologist</option>
                        <option value="Neurologist">Neurologist</option>
                        <option value="Pediatrician">Pediatrician</option>
                        <option value="General Physician">General Physician</option>
                        <option value="Orthopedic Surgeon">Orthopedic Surgeon</option>
                        <option value="Gynecologist">Gynecologist</option>
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
            </div>
            <div class="text-center mt-4">
                <button class="btn btn-primary" onclick="findNearestDoctor()">Find Nearest Doctor</button>
            </div>
        </div>

        <!-- Doctor List -->
        <div id="doctorList" class="row"></div>
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
        // Sample doctor data (Bangladeshi context)
        const doctors = [
            { name: "Dr. Md. Abdul Karim", gender: "Male", specialty: "Cardiologist", city: "Dhaka", distance: 2.5, image: "https://via.placeholder.com/130?text=Dr+Karim", qualifications: "MBBS, FCPS (Cardiology)", experience: "15 years", hospital: "National Heart Foundation Hospital" },
            { name: "Dr. Farhana Rahman", gender: "Female", specialty: "Dermatologist", city: "Dhaka", distance: 4.8, image: "https://via.placeholder.com/130?text=Dr+Rahman", qualifications: "MBBS, MD (Dermatology)", experience: "10 years", hospital: "Labaid Specialized Hospital" },
            { name: "Dr. Shahidul Islam", gender: "Male", specialty: "Neurologist", city: "Chattogram", distance: 10.2, image: "https://via.placeholder.com/130?text=Dr+Islam", qualifications: "MBBS, FCPS (Neurology)", experience: "12 years", hospital: "Chittagong Medical College Hospital" },
            { name: "Dr. Nusrat Jahan", gender: "Female", specialty: "Pediatrician", city: "Sylhet", distance: 15.7, image: "https://via.placeholder.com/130?text=Dr+Jahan", qualifications: "MBBS, DCH", experience: "8 years", hospital: "Sylhet MAG Osmani Medical College" },
            { name: "Dr. Md. Rafiqul Alam", gender: "Male", specialty: "General Physician", city: "Dhaka", distance: 3.1, image: "https://via.placeholder.com/130?text=Dr+Alam", qualifications: "MBBS, MD (Internal Medicine)", experience: "20 years", hospital: "United Hospital" },
            { name: "Dr. Ayesha Siddiqua", gender: "Female", specialty: "Gynecologist", city: "Dhaka", distance: 5.0, image: "https://via.placeholder.com/130?text=Dr+Siddiqua", qualifications: "MBBS, FCPS (Gynecology)", experience: "14 years", hospital: "Apollo Hospital Dhaka" },
            { name: "Dr. Md. Kamrul Hasan", gender: "Male", specialty: "Orthopedic Surgeon", city: "Rajshahi", distance: 12.3, image: "https://via.placeholder.com/130?text=Dr+Hasan", qualifications: "MBBS, MS (Orthopedics)", experience: "9 years", hospital: "Rajshahi Medical College Hospital" },
            { name: "Dr. Sabrina Chowdhury", gender: "Female", specialty: "Neurologist", city: "Dhaka", distance: 7.8, image: "https://via.placeholder.com/130?text=Dr+Chowdhury", qualifications: "MBBS, MD (Neurology)", experience: "11 years", hospital: "Bangabandhu Sheikh Mujib Medical University" },
            { name: "Dr. Imran Hossain", gender: "Male", specialty: "Pediatrician", city: "Chattogram", distance: 9.4, image: "https://via.placeholder.com/130?text=Dr+Hossain", qualifications: "MBBS, DCH", experience: "7 years", hospital: "Chattogram General Hospital" },
            { name: "Dr. Fatema Begum", gender: "Female", specialty: "General Physician", city: "Sylhet", distance: 18.2, image: "https://via.placeholder.com/130?text=Dr+Begum", qualifications: "MBBS, FCPS (Medicine)", experience: "16 years", hospital: "Sylhet Women’s Medical College" },
            { name: "Dr. Md. Sohel Rana", gender: "Male", specialty: "Cardiologist", city: "Dhaka", distance: 6.5, image: "https://via.placeholder.com/130?text=Dr+Rana", qualifications: "MBBS, FCPS (Cardiology)", experience: "13 years", hospital: "Ibn Sina Hospital" },
            { name: "Dr. Tahmina Akter", gender: "Female", specialty: "Dermatologist", city: "Dhaka", distance: 3.9, image: "https://via.placeholder.com/130?text=Dr+Akter", qualifications: "MBBS, MD (Dermatology)", experience: "8 years", hospital: "Popular Diagnostic Centre" },
            { name: "Dr. Arifur Rahman", gender: "Male", specialty: "Neurologist", city: "Rajshahi", distance: 14.6, image: "https://via.placeholder.com/130?text=Dr+Rahman", qualifications: "MBBS, FCPS (Neurology)", experience: "10 years", hospital: "Rajshahi General Hospital" },
            { name: "Dr. Shahnaz Parvin", gender: "Female", specialty: "Pediatrician", city: "Dhaka", distance: 4.2, image: "https://via.placeholder.com/130?text=Dr+Parvin", qualifications: "MBBS, DCH", experience: "6 years", hospital: "Dhaka Shishu Hospital" },
            { name: "Dr. Md. Ehsan Ali", gender: "Male", specialty: "General Physician", city: "Chattogram", distance: 11.1, image: "https://via.placeholder.com/130?text=Dr+Ali", qualifications: "MBBS, MD (Internal Medicine)", experience: "18 years", hospital: "Chattogram Medical Centre" },
            { name: "Dr. Mehnaz Sultana", gender: "Female", specialty: "Gynecologist", city: "Sylhet", distance: 16.8, image: "https://via.placeholder.com/130?text=Dr+Sultana", qualifications: "MBBS, FCPS (Gynecology)", experience: "12 years", hospital: "Sylhet Central Hospital" },
            { name: "Dr. Zakir Hossain", gender: "Male", specialty: "Dermatologist", city: "Dhaka", distance: 5.6, image: "https://via.placeholder.com/130?text=Dr+Hossain", qualifications: "MBBS, MD (Dermatology)", experience: "9 years", hospital: "Evercare Hospital Dhaka" },
            { name: "Dr. Runa Laila", gender: "Female", specialty: "Neurologist", city: "Dhaka", distance: 8.3, image: "https://via.placeholder.com/130?text=Dr+Laila", qualifications: "MBBS, FCPS (Neurology)", experience: "11 years", hospital: "National Institute of Neurosciences" },
            { name: "Dr. Asif Mahmud", gender: "Male", specialty: "Orthopedic Surgeon", city: "Rajshahi", distance: 13.5, image: "https://via.placeholder.com/130?text=Dr+Mahmud", qualifications: "MBBS, MS (Orthopedics)", experience: "7 years", hospital: "Rajshahi Children’s Hospital" },
            { name: "Dr. Shahnaz Begum", gender: "Female", specialty: "General Physician", city: "Dhaka", distance: 2.9, image: "https://via.placeholder.com/130?text=Dr+Begum", qualifications: "MBBS, FCPS (Medicine)", experience: "15 years", hospital: "Green Life Medical College" },
            { name: "Dr. Md. Monirul Islam", gender: "Male", specialty: "Cardiologist", city: "Chattogram", distance: 10.7, image: "https://via.placeholder.com/130?text=Dr+Islam", qualifications: "MBBS, FCPS (Cardiology)", experience: "14 years", hospital: "Chattogram Heart Foundation" },
            { name: "Dr. Fatema Zohra", gender: "Female", specialty: "Dermatologist", city: "Sylhet", distance: 17.4, image: "https://via.placeholder.com/130?text=Dr+Zohra", qualifications: "MBBS, MD (Dermatology)", experience: "8 years", hospital: "Sylhet Skin Care Centre" },
            { name: "Dr. Md. Rezaul Karim", gender: "Male", specialty: "Neurologist", city: "Dhaka", distance: 6.1, image: "https://via.placeholder.com/130?text=Dr+Karim", qualifications: "MBBS, FCPS (Neurology)", experience: "12 years", hospital: "Dhaka Medical College Hospital" },
            { name: "Dr. Nasrin Sultana", gender: "Female", specialty: "Pediatrician", city: "Dhaka", distance: 4.7, image: "https://via.placeholder.com/130?text=Dr+Sultana", qualifications: "MBBS, DCH", experience: "9 years", hospital: "Anwer Khan Modern Hospital" },
            { name: "Dr. Md. Rafiqul Islam", gender: "Male", specialty: "General Physician", city: "Rajshahi", distance: 15.3, image: "https://via.placeholder.com/130?text=Dr+Islam", qualifications: "MBBS, MD (Internal Medicine)", experience: "17 years", hospital: "Rajshahi Medical Centre" }
        ];

        // Render doctor list
        function renderDoctors(filteredDoctors) {
            const doctorList = document.getElementById('doctorList');
            doctorList.innerHTML = '';
            filteredDoctors.forEach(doctor => {
                const doctorCard = document.createElement('div');
                doctorCard.className = 'col-md-6 col-lg-4';
                doctorCard.innerHTML = `
                    <div class="doctor-card">
                        <img src="${doctor.image}" alt="${doctor.name}">
                        <div class="doctor-info">
                            <h5>${doctor.name}</h5>
                            <p><strong>Specialty:</strong> ${doctor.specialty}</p>
                            <p><strong>Qualifications:</strong> ${doctor.qualifications}</p>
                            <p><strong>Experience:</strong> ${doctor.experience}</p>
                            <p><strong>Hospital:</strong> ${doctor.hospital}</p>
                            <p><strong>Location:</strong> ${doctor.city}</p>
                            <p class="distance"><strong>Distance:</strong> ${doctor.distance} km</p>
                            <a href="#" class="btn-book">Book Appointment</a>
                        </div>
                    </div>
                `;
                doctorList.appendChild(doctorCard);
            });
        }

        // Filter doctors
        function filterDoctors() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const gender = document.getElementById('genderFilter').value;
            const specialty = document.getElementById('specialtyFilter').value;
            const city = document.getElementById('cityFilter').value;
            const distance = document.getElementById('distanceFilter').value;

            const filteredDoctors = doctors.filter(doctor => {
                return (
                    (search === '' || doctor.name.toLowerCase().includes(search) || doctor.specialty.toLowerCase().includes(search)) &&
                    (gender === '' || doctor.gender === gender) &&
                    (specialty === '' || doctor.specialty === specialty) &&
                    (city === '' || doctor.city === city) &&
                    (distance === '' || doctor.distance <= parseFloat(distance))
                );
            });

            renderDoctors(filteredDoctors);
        }

        // Find nearest doctor using Geolocation API
        function findNearestDoctor() {
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
                    doctors.forEach(doctor => {
                        const city = cityCoords[doctor.city] || { lat: userLat, lon: userLon };
                        doctor.distance = calculateDistance(userLat, userLon, city.lat, city.lon);
                    });

                    // Sort by distance
                    doctors.sort((a, b) => a.distance - b.distance);

                    // Render updated list
                    filterDoctors();
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
        document.getElementById('searchInput').addEventListener('input', filterDoctors);
        document.getElementById('genderFilter').addEventListener('change', filterDoctors);
        document.getElementById('specialtyFilter').addEventListener('change', filterDoctors);
        document.getElementById('cityFilter').addEventListener('change', filterDoctors);
        document.getElementById('distanceFilter').addEventListener('change', filterDoctors);

        // Initial render
        renderDoctors(doctors);
    </script>
</body>
</html>