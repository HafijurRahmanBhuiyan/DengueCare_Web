<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENGUECARE : FIND HOSPITAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        .hover-scale:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        .hospital-card {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .hospital-card:hover {
            background-color: #e6f3ff;
            transform: translateY(-5px);
        }
        #map {
            height: 400px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .connected-message {
            animation: fadeIn 1s ease-in-out;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        .leaflet-popup-tip {
            background: #2563eb;
        }
        #hospitalBoxList {
            max-height: 400px;
            overflow-y: auto;
            background: #f9fafb;
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>
<body class="min-h-screen bg-gray-100 flex flex-col items-center p-6">
    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl p-8">
        <!-- Header -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-blue-700">DengueCare Hospital Management</h1>
            <p class="text-gray-600 mt-2">Find and connect to top hospitals in Bangladesh for dengue treatment</p>
        </header>

        <!-- Filter System -->
        <div id="filterHospital" class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Filter Facilities</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <input type="text" id="nameFilter" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition-all" placeholder="Hospital Name" oninput="filterHospitals()">
                <input type="text" id="cityFilter" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition-all" placeholder="City (e.g., Dhaka, Sylhet)" oninput="filterHospitals()">
                <select id="typeFilter" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition-all" onchange="filterHospitals()">
                    <option value="">All Types</option>
                    <option value="General">General Hospital</option>
                    <option value="Specialized">Specialized Hospital</option>
                    <option value="Diagnostic">Diagnostic Center</option>
                </select>
                <select id="districtFilter" class="p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 transition-all" onchange="filterHospitals()">
                    <option value="">All Districts</option>
                    <option value="Dhaka">Dhaka</option>
                    <option value="Chattogram">Chattogram</option>
                    <option value="Sylhet">Sylhet</option>
                    <option value="Bogra">Bogra</option>
                    <option value="Cox’s Bazar">Cox’s Bazar</option>
                    <option value="Mymensingh">Mymensingh</option>
                    <option value="Sirajganj">Sirajganj</option>
                    <option value="Kishoreganj">Kishoreganj</option>
                    <option value="Tangail">Tangail</option>
                    <option value="Jhalakati">Jhalakati</option>
                </select>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <button onclick="findNearestHospital()" class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hover-scale transition-all w-full md:w-auto">Find Nearest Hospital</button>
                <button onclick="showAllHospitals()" class="p-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 hover-scale transition-all w-full md:w-auto">Show All Hospitals</button>
            </div>
        </div>

        <!-- Suggested Hospitals -->
        <div id="suggestedHospitals" class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Suggested Hospitals</h2>
            <div id="suggestedCards" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
        </div>

        <!-- Hospital List -->
        <div id="hospitalList" class="mb-10">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">All Facilities</h2>
            <div id="hospitalCards"></div>
        </div>

        <!-- Map and Hospital Boxes -->
        <div class="flex flex-col md:flex-row gap-4 mb-10">
            <div id="map" class="w-full md:w-3/4"></div>
            <div id="hospitalBoxList" class="w-full md:w-1/4">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Hospitals on Map</h3>
                <div id="hospitalBoxes"></div>
            </div>
        </div>

        <!-- Connection Status -->
        <div id="connectionStatus" class="hidden text-center"></div>
    </div>

    <script>
        let map;
        let markers = L.markerClusterGroup({
            iconCreateFunction: function(cluster) {
                return L.divIcon({
                    html: `<div class="bg-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">${cluster.getChildCount()}</div>`,
                    className: 'marker-cluster',
                    iconSize: L.point(40, 40)
                });
            }
        });
        let filteredHospitals = [];
        let userLocation = null;
        let nearestHospitalMode = false;

        // Initialize Leaflet map centered on Bangladesh
        function initMap() {
            map = L.map('map').setView([23.8103, 90.4125], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                className: 'map-tiles'
            }).addTo(map);
            map.addLayer(markers);
        }

        // Hospital data (50 hospitals with district)
        const hospitals = [
            { name: "Evercare Hospital Dhaka", location: "Bashundhara R/A, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8103, lng: 90.4125, type: "Specialized" },
            { name: "Square Hospital", location: "Panthapath, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7530, lng: 90.3817, type: "Specialized" },
            { name: "DNCC Dedicated Dengue Hospital", location: "Mohakhali, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7776, lng: 90.4097, type: "General" },
            { name: "Bangabandhu Sheikh Mujib Medical University", location: "Shahbagh, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7389, lng: 90.3984, type: "General" },
            { name: "Dhaka Medical College Hospital", location: "Bakshibazar, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7256, lng: 90.3987, type: "General" },
            { name: "United Hospital Limited", location: "Gulshan-2, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8045, lng: 90.4156, type: "Specialized" },
            { name: "Labaid Specialized Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7407, lng: 90.3736, type: "Specialized" },
            { name: "BIRDEM General Hospital", location: "Shahbagh, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7385, lng: 90.3968, type: "Specialized" },
            { name: "National Hospital Chattogram", location: "Panchlaish, Chattogram", city: "Chattogram", district: "Chattogram", lat: 22.3619, lng: 91.8301, type: "General" },
            { name: "Chattogram Medical College Hospital", location: "Panchlaish, Chattogram", city: "Chattogram", district: "Chattogram", lat: 22.3596, lng: 91.8289, type: "General" },
            { name: "Green Life Medical College Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7432, lng: 90.3832, type: "General" },
            { name: "Anwer Khan Modern Medical College", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7410, lng: 90.3707, type: "General" },
            { name: "Bangladesh Specialized Hospital", location: "Shyamoli, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7709, lng: 90.3690, type: "Specialized" },
            { name: "Asgar Ali Hospital", location: "Gandaria, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7026, lng: 90.4264, type: "Specialized" },
            { name: "Holy Family Red Crescent Medical College Hospital", location: "Moghbazar, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7479, lng: 90.4057, type: "General" },
            { name: "Combined Military Hospital (CMH)", location: "Dhaka Cantonment, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8167, lng: 90.4007, type: "General" },
            { name: "Kurmitola General Hospital", location: "Khilkhet, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8217, lng: 90.4097, type: "General" },
            { name: "Uttara Crescent Hospital", location: "Uttara, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8678, lng: 90.4024, type: "Specialized" },
            { name: "Enam Medical College Hospital", location: "Savar, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8423, lng: 90.2527, type: "General" },
            { name: "Bangladesh Medical College Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7465, lng: 90.3740, type: "General" },
            { name: "Al-Helal Specialist Hospital", location: "Mirpur, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7889, lng: 90.3537, type: "Specialized" },
            { name: "Ayesha Memorial Specialized Hospital", location: "Mohakhali, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7765, lng: 90.4062, type: "Specialized" },
            { name: "Central Hospital Dhaka", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7438, lng: 90.3825, type: "Specialized" },
            { name: "Shamorita Hospital", location: "Panthapath, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7523, lng: 90.3845, type: "General" },
            { name: "Khwaja Yunus Ali Medical College and Hospital", location: "Sirajganj", city: "Sirajganj", district: "Sirajganj", lat: 24.4583, lng: 89.7016, type: "General" },
            { name: "Jahirul Islam Medical College and Hospital", location: "Kishoreganj", city: "Kishoreganj", district: "Kishoreganj", lat: 24.4387, lng: 90.7829, type: "General" },
            { name: "Kumudini Medical College and Hospital", location: "Tangail", city: "Tangail", district: "Tangail", lat: 24.2638, lng: 89.9157, type: "General" },
            { name: "Noorjahan Hospital", location: "Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.8990, lng: 91.8687, type: "Specialized" },
            { name: "Oasis Hospital Sylhet", location: "Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.8967, lng: 91.8712, type: "Specialized" },
            { name: "Jalalabad Ragib-Rabeya Medical College", location: "Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.9138, lng: 91.8678, type: "General" },
            { name: "TMSS Medical College and Hospital", location: "Bogra", city: "Bogra", district: "Bogra", lat: 24.8508, lng: 89.3725, type: "General" },
            { name: "Fuad Al-Khatib Hospital", location: "Cox’s Bazar", city: "Cox’s Bazar", district: "Cox’s Bazar", lat: 21.4395, lng: 91.9762, type: "General" },
            { name: "Chattagram Maa-Shishu o General Hospital", location: "Agrabad, Chattogram", city: "Chattogram", district: "Chattogram", lat: 22.3271, lng: 91.8024, type: "General" },
            { name: "Chittagong Metropolitan Hospital Pvt Ltd", location: "GEC Circle, Chattogram", city: "Chattogram", district: "Chattogram", lat: 22.3519, lng: 91.8214, type: "Specialized" },
            { name: "Community-Based Medical College Hospital", location: "Mymensingh", city: "Mymensingh", district: "Mymensingh", lat: 24.7431, lng: 90.3982, type: "General" },
            { name: "K Zaman BNSB Eye Hospital", location: "Mymensingh", city: "Mymensingh", district: "Mymensingh", lat: 24.7562, lng: 90.4067, type: "Specialized" },
            { name: "Ibn Sina Specialized Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7450, lng: 90.3728, type: "Specialized" },
            { name: "Popular Diagnostic Centre", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7425, lng: 90.3755, type: "Diagnostic" },
            { name: "National Heart Foundation Hospital", location: "Mirpur, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7993, lng: 90.3522, type: "Specialized" },
            { name: "Labaid Cardiac Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7398, lng: 90.3745, type: "Specialized" },
            { name: "Mount Adora Hospital", location: "Nayasarak, Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.9012, lng: 91.8675, type: "Specialized" },
            { name: "Al Haramain Hospital", location: "Subhani Ghat, Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.8887, lng: 91.8743, type: "General" },
            { name: "Bangladesh Eye Hospital", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7460, lng: 90.3700, type: "Specialized" },
            { name: "Medinova Medical Services Ltd", location: "Dhanmondi, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7445, lng: 90.3765, type: "Diagnostic" },
            { name: "Kuwait Bangladesh Friendship Govt. Hospital", location: "Uttara, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8745, lng: 90.4000, type: "General" },
            { name: "Greenland Hospital Limited", location: "Uttara, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.8670, lng: 90.4032, type: "General" },
            { name: "Chander Hasi Hospital Limited", location: "Habiganj, Sylhet", city: "Sylhet", district: "Sylhet", lat: 24.3788, lng: 91.4157, type: "General" },
            { name: "Cox’s Bazar Hospital for Women & Children", location: "Cox’s Bazar", city: "Cox’s Bazar", district: "Cox’s Bazar", lat: 21.4423, lng: 91.9785, type: "Specialized" },
            { name: "Dhaka Central International Medical College Hospital", location: "Adabor, Dhaka", city: "Dhaka", district: "Dhaka", lat: 23.7712, lng: 90.3635, type: "General" },
            { name: "Dr. Alauddin Ahmed Clinic", location: "Jhalakati", city: "Jhalakati", district: "Jhalakati", lat: 22.6423, lng: 90.1987, type: "General" }
        ];

        // Find nearest hospital using GPS
        function findNearestHospital() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        userLocation = { lat: position.coords.latitude, lng: position.coords.longitude };
                        nearestHospitalMode = true;
                        filterHospitals();
                    },
                    () => {
                        // Fallback to simulated location (Dhaka)
                        userLocation = { lat: 23.8103, lng: 90.4125 };
                        nearestHospitalMode = true;
                        alert('Unable to detect location. Using default location (Dhaka).');
                        filterHospitals();
                    }
                );
            } else {
                // Fallback to simulated location
                userLocation = { lat: 23.8103, lng: 90.4125 };
                nearestHospitalMode = true;
                alert('Geolocation not supported. Using default location (Dhaka).');
                filterHospitals();
            }
        }

        // Show all hospitals
        function showAllHospitals() {
            nearestHospitalMode = false;
            document.getElementById('nameFilter').value = '';
            document.getElementById('cityFilter').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('districtFilter').value = '';
            userLocation = null;
            filterHospitals();
        }

        // Calculate distance between two coordinates (Haversine formula)
        function getDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Earth's radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLng / 2) * Math.sin(dLng / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c; // Distance in km
        }

        // Filter hospitals and handle nearest hospital mode
        function filterHospitals() {
            const nameFilter = document.getElementById('nameFilter').value.toLowerCase();
            const cityFilter = document.getElementById('cityFilter').value.toLowerCase();
            const typeFilter = document.getElementById('typeFilter').value;
            const districtFilter = document.getElementById('districtFilter').value;

            if (nearestHospitalMode && userLocation) {
                // Find nearest hospital
                let nearest = null;
                let minDistance = Infinity;
                hospitals.forEach(hospital => {
                    const distance = getDistance(userLocation.lat, userLocation.lng, hospital.lat, hospital.lng);
                    if (distance < minDistance) {
                        minDistance = distance;
                        nearest = hospital;
                    }
                });
                filteredHospitals = nearest ? [nearest] : [];
            } else {
                // Apply regular filters
                filteredHospitals = hospitals.filter(hospital =>
                    (!nameFilter || hospital.name.toLowerCase().includes(nameFilter)) &&
                    (!cityFilter || hospital.city.toLowerCase().includes(cityFilter)) &&
                    (!typeFilter || hospital.type === typeFilter) &&
                    (!districtFilter || hospital.district === districtFilter)
                );
            }

            // Sort by distance if user location is available and not in nearest mode
            if (userLocation && !nearestHospitalMode) {
                filteredHospitals.sort((a, b) => {
                    const distA = getDistance(userLocation.lat, userLocation.lng, a.lat, a.lng);
                    const distB = getDistance(userLocation.lat, userLocation.lng, b.lat, b.lng);
                    return distA - distB;
                });
            }

            updateSuggestedHospitals();
            updateHospitalList();
            updateMap();
            updateHospitalBoxes();
        }

        // Update suggested hospitals (5-6 based on filter or proximity)
        function updateSuggestedHospitals() {
            const suggestedCards = document.getElementById('suggestedCards');
            suggestedCards.innerHTML = '';
            const suggested = nearestHospitalMode ? filteredHospitals : (filteredHospitals.length > 0 ? filteredHospitals.slice(0, 6) : hospitals.slice(0, 6));
            suggested.forEach((hospital, index) => {
                const originalIndex = hospitals.indexOf(hospital);
                const card = document.createElement('div');
                card.className = 'hospital-card bg-gray-50 p-4 rounded-lg flex justify-between items-center';
                const distance = userLocation ? getDistance(userLocation.lat, userLocation.lng, hospital.lat, hospital.lng).toFixed(2) : 'N/A';
                card.innerHTML = `
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">${hospital.name}</h3>
                        <p class="text-gray-600">${hospital.location} (${hospital.type})</p>
                        <p class="text-gray-500">Distance: ${distance} km</p>
                    </div>
                    <button onclick="connectHospital(${originalIndex})" class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hover-scale transition-all">Connect</button>
                `;
                suggestedCards.appendChild(card);
            });
        }

        // Update hospital list UI
        function updateHospitalList() {
            const hospitalCards = document.getElementById('hospitalCards');
            hospitalCards.innerHTML = '';
            const hospitalsToShow = nearestHospitalMode ? filteredHospitals : (filteredHospitals.length > 0 ? filteredHospitals : hospitals);
            if (hospitalsToShow.length === 0) {
                hospitalCards.innerHTML = '<p class="text-gray-600">No hospitals found.</p>';
                return;
            }
            hospitalsToShow.forEach((hospital, index) => {
                const originalIndex = hospitals.indexOf(hospital);
                const card = document.createElement('div');
                card.className = 'hospital-card bg-gray-50 p-4 rounded-lg mb-4 flex justify-between items-center';
                const distance = userLocation ? getDistance(userLocation.lat, userLocation.lng, hospital.lat, hospital.lng).toFixed(2) : 'N/A';
                card.innerHTML = `
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">${hospital.name}</h3>
                        <p class="text-gray-600">${hospital.location} (${hospital.type})</p>
                        <p class="text-gray-500">Distance: ${distance} km</p>
                    </div>
                    <button onclick="connectHospital(${originalIndex})" class="p-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hover-scale transition-all">Connect</button>
                `;
                hospitalCards.appendChild(card);
            });
        }

        // Update map with markers
        function updateMap() {
            markers.clearLayers();
            const hospitalsToShow = nearestHospitalMode ? filteredHospitals : (filteredHospitals.length > 0 ? filteredHospitals : hospitals);
            hospitalsToShow.forEach(hospital => {
                const marker = L.marker([hospital.lat, hospital.lng], {
                    icon: L.divIcon({
                        html: `<div class="bg-blue-600 w-6 h-6 rounded-full border-2 border-white"></div>`,
                        className: 'custom-marker',
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    })
                }).bindPopup(`<b>${hospital.name}</b><br>${hospital.location}<br>${hospital.type}`);
                markers.addLayer(marker);
            });
            if (hospitalsToShow.length > 0) {
                map.fitBounds(markers.getBounds());
            } else {
                map.setView([23.8103, 90.4125], 7);
            }
            // Add user location marker if available
            if (userLocation) {
                L.marker([userLocation.lat, userLocation.lng], {
                    icon: L.divIcon({
                        html: `<div class="bg-red-600 w-8 h-8 rounded-full border-2 border-white"></div>`,
                        className: 'user-marker',
                        iconSize: [32, 32],
                        iconAnchor: [16, 16]
                    })
                }).addTo(map).bindPopup('<b>Your Location</b>');
            }
        }

        // Update hospital boxes on the right side of the map
        function updateHospitalBoxes() {
            const hospitalBoxes = document.getElementById('hospitalBoxes');
            hospitalBoxes.innerHTML = '';
            const hospitalsToShow = nearestHospitalMode ? filteredHospitals : (filteredHospitals.length > 0 ? filteredHospitals : hospitals);
            if (hospitalsToShow.length === 0) {
                hospitalBoxes.innerHTML = '<p class="text-gray-600">No hospitals found.</p>';
                return;
            }
            hospitalsToShow.forEach(hospital => {
                const box = document.createElement('div');
                box.className = 'hospital-card bg-gray-50 p-3 rounded-lg mb-2';
                const distance = userLocation ? getDistance(userLocation.lat, userLocation.lng, hospital.lat, hospital.lng).toFixed(2) : 'N/A';
                box.innerHTML = `
                    <h3 class="text-md font-semibold text-gray-700">${hospital.name}</h3>
                    <p class="text-gray-600 text-sm">${hospital.location} (${hospital.type})</p>
                    <p class="text-gray-500 text-sm">Distance: ${distance} km</p>
                `;
                box.onclick = () => {
                    map.setView([hospital.lat, hospital.lng], 12);
                    markers.eachLayer(marker => {
                        if (marker.getLatLng().lat === hospital.lat && marker.getLatLng().lng === hospital.lng) {
                            marker.openPopup();
                        }
                    });
                };
                hospitalBoxes.appendChild(box);
            });
        }

        // Connect to hospital (simulates dengue patient connection)
        function connectHospital(index) {
            const hospital = hospitals[index];
            const connectionStatus = document.getElementById('connectionStatus');
            connectionStatus.classList.remove('hidden');
            connectionStatus.innerHTML = `
                <div class="connected-message bg-green-100 p-6 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-green-600 mt-4">Hospital Connected</h2>
                    <p class="text-gray-600">You are connected to <strong>${hospital.name}</strong> at ${hospital.location} for dengue treatment.</p>
                </div>
            `;
            connectionStatus.scrollIntoView({ behavior: 'smooth' });
        }

        // Initialize
        initMap();
        filteredHospitals = hospitals;
        updateSuggestedHospitals();
        updateHospitalList();
        updateMap();
        updateHospitalBoxes();
    </script>
</body>
</html>