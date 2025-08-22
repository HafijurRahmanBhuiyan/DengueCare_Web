<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangladesh Dengue Heatmap (2023)</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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
        /* Map and Table Styles */
        #map { height: 600px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hover-scale:hover { transform: scale(1.02); transition: transform 0.2s ease; }
        .top-district { background-color: #fef2f2; font-weight: 600; }
        .leaflet-popup-content-wrapper { border-radius: 8px; font-family: 'Inter', sans-serif; }
        .leaflet-popup-tip { background: #b91c1c; }
        .legend { background: white; padding: 12px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); font-family: 'Inter', sans-serif; }
        .legend i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.8; }
        th:hover { background-color: #991b1b; transition: background-color 0.3s; }
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
                    <a href="heatmap.html" class="nav-item nav-link active">HEAT MAP</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a class="btn btn-outline-light py-2 px-3" href="">
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

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-xl p-8">
            <!-- Map -->
            <div id="map" class="w-full md:w-3/4 mx-auto mb-12"></div>

            <!-- Data Table -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Dengue Case Data</h2>
                <div class="overflow-x-auto">
                    <table id="districtTable" class="w-full table-auto border-collapse bg-white rounded-lg shadow-sm">
                        <thead>
                            <tr class="bg-red-600 text-white">
                                <th class="p-4 text-left cursor-pointer rounded-tl-lg" onclick="sortTable(0)">District</th>
                                <th class="p-4 text-left cursor-pointer" onclick="sortTable(1)">Dengue Cases</th>
                                <th class="p-4 text-left cursor-pointer rounded-tr-lg" onclick="sortTable(2)">Prevalence Rate</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
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
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // District data with centroids (2023, estimated)
        const districtData = [
            { district: "Dhaka", cases: 100717, population: 12043977, prevalence: 83.62, lat: 23.8103, lng: 90.4125 },
            { district: "Chattogram", cases: 36600, population: 7616352, prevalence: 48.04, lat: 22.3569, lng: 91.7832 },
            { district: "Barisal", cases: 29100, population: 2323310, prevalence: 125.27, lat: 22.7010, lng: 90.3535 },
            { district: "Patuakhali", cases: 15000, population: 1535854, prevalence: 97.67, lat: 22.3596, lng: 90.3298 },
            { district: "Lakshmipur", cases: 12000, population: 1729139, prevalence: 69.41, lat: 22.9443, lng: 90.8299 },
            { district: "Pirojpur", cases: 10000, population: 1113209, prevalence: 89.82, lat: 22.5791, lng: 89.9752 },
            { district: "Chandpur", cases: 11000, population: 2416018, prevalence: 45.53, lat: 23.2513, lng: 90.6710 },
            { district: "Manikganj", cases: 13000, population: 1393867, prevalence: 93.25, lat: 23.8617, lng: 90.0003 },
            { district: "Cumilla", cases: 14000, population: 5390228, prevalence: 25.97, lat: 23.4682, lng: 91.1788 },
            { district: "Faridpur", cases: 12000, population: 1915969, prevalence: 62.63, lat: 23.6071, lng: 89.8427 },
            { district: "Bagerhat", cases: 9000, population: 1476090, prevalence: 60.97, lat: 22.6576, lng: 89.7895 },
            { district: "Jhalokati", cases: 8000, population: 682669, prevalence: 117.18, lat: 22.6408, lng: 90.1987 },
            { district: "Barguna", cases: 7000, population: 892781, prevalence: 78.43, lat: 22.1591, lng: 90.1264 },
            { district: "Cox’s Bazar", cases: 15000, population: 2289990, prevalence: 65.50, lat: 21.4272, lng: 92.0058 },
            { district: "Shariatpur", cases: 10000, population: 1155824, prevalence: 86.52, lat: 23.2423, lng: 90.4348 }
        ];

        // Initialize Leaflet map
        const map = L.map('map', {
            maxBounds: [[20.5, 88.0], [26.5, 92.5]],
            maxBoundsViscosity: 1.0
        }).setView([23.6850, 90.3563], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            minZoom: 6,
            maxZoom: 12
        }).addTo(map);

        // Color scale (white to red)
        function getColor(cases) {
            const maxCases = Math.max(...districtData.map(d => d.cases));
            const intensity = cases / maxCases;
            const r = Math.round(255 * intensity);
            return `rgb(${r}, ${255 - r}, ${255 - r})`;
        }

        // Radius scale (logarithmic for better visualization)
        function getRadius(cases) {
            const maxCases = Math.max(...districtData.map(d => d.cases));
            const minRadius = 5;
            const maxRadius = 50;
            const logScale = Math.log(cases) / Math.log(maxCases);
            return minRadius + (maxRadius - minRadius) * logScale;
        }

        // Add circle markers
        districtData.forEach(data => {
            const isTop5 = districtData.sort((a, b) => b.cases - a.cases).slice(0, 5).some(d => d.district === data.district);
            L.circleMarker([data.lat, data.lng], {
                radius: getRadius(data.cases),
                fillColor: getColor(data.cases),
                color: isTop5 ? '#991b1b' : '#6b7280',
                weight: isTop5 ? 2 : 1,
                fillOpacity: 0.4
            })
            .bindTooltip(`
                <div class='text-sm'>
                    <b>${data.district}</b><br>
                    Cases: ${data.cases.toLocaleString()}<br>
                    Prevalence: ${data.prevalence.toFixed(2)} per 10,000
                </div>
            `, { sticky: true })
            .on('mouseover', function (e) {
                this.setStyle({ fillOpacity: 0.6, weight: 3, color: '#991b1b' });
            })
            .on('mouseout', function (e) {
                this.setStyle({
                    fillOpacity: 0.4,
                    weight: isTop5 ? 2 : 1,
                    color: isTop5 ? '#991b1b' : '#6b7280'
                });
            })
            .on('click', function (e) {
                map.setView([data.lat, data.lng], 9);
            })
            .addTo(map);
        });

        // Add legend
        const legend = L.control({ position: 'bottomright' });
        legend.onAdd = function () {
            const div = L.DomUtil.create('div', 'legend');
            const maxCases = Math.max(...districtData.map(d => d.cases));
            const steps = 5;
            const stepSize = maxCases / steps;
            div.innerHTML = '<h4 class="text-sm font-semibold">Dengue Cases</h4>';
            for (let i = 0; i <= steps; i++) {
                const value = Math.round(i * stepSize);
                const radius = getRadius(value) / 2;
                div.innerHTML += `
                    <div class="flex items-center text-xs">
                        <i style="background:${getColor(value)}; width:${radius}px; height:${radius}px; border-radius:50%"></i>
                        ${value.toLocaleString()}${i === steps ? '+' : ''}
                    </div>
                `;
            }
            return div;
        };
        legend.addTo(map);

        // Populate table
        function populateTable() {
            const tbody = document.getElementById('tableBody');
            const sortedData = [...districtData].sort((a, b) => b.cases - a.cases);
            tbody.innerHTML = '';
            sortedData.forEach((item, index) => {
                const row = document.createElement('tr');
                row.className = index < 5 ? 'top-district fade-in hover-scale' : 'fade-in hover-scale';
                row.innerHTML = `
                    <td class="p-4">${item.district}</td>
                    <td class="p-4">${item.cases.toLocaleString()}</td>
                    <td class="p-4">${item.prevalence.toFixed(2)}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Sort table
        let sortDirection = 1;
        function sortTable(column) {
            districtData.sort((a, b) => {
                const valA = column === 0 ? a.district : column === 1 ? a.cases : a.prevalence;
                const valB = column === 0 ? b.district : column === 1 ? b.cases : b.prevalence;
                return column === 0
                    ? valA.localeCompare(valB) * sortDirection
                    : (valA - valB) * sortDirection;
            });
            sortDirection *= -1;
            populateTable();
        }

        // Initial table population
        populateTable();
    </script>
</body>
</html>