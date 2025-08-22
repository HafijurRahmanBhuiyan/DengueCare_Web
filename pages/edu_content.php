<?php
// Hardcoded video data (30 videos: 15 Dengue, 15 General Health)
$videos = [
    ['title' => 'Dengue Fever: Symptoms and Treatment', 'description' => 'Learn about the symptoms of dengue fever and how it is treated.', 'youtube_url' => 'https://www.youtube.com/watch?v=51AECqscavc', 'thumbnail_url' => 'https://img.youtube.com/vi/0T_4eRZeV1I/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'How to Prevent Dengue Fever', 'description' => 'Tips to protect yourself from dengue by avoiding mosquito bites.', 'youtube_url' => 'https://www.youtube.com/embed/2uU4G8c4fI4', 'thumbnail_url' => 'https://img.youtube.com/vi/2uU4G8c4fI4/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Understanding Dengue Virus', 'description' => 'An overview of the dengue virus and its transmission.', 'youtube_url' => 'https://www.youtube.com/embed/QE7xWtk7iUc', 'thumbnail_url' => 'https://img.youtube.com/vi/QE7xWtk7iUc/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue: Diagnosis and Management', 'description' => 'How doctors diagnose and manage dengue cases.', 'youtube_url' => 'https://www.youtube.com/embed/8Qw9S6W3y1g', 'thumbnail_url' => 'https://img.youtube.com/vi/8Qw9S6W3y1g/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Mosquito Control for Dengue Prevention', 'description' => 'Strategies to control mosquito breeding to prevent dengue.', 'youtube_url' => 'https://www.youtube.com/embed/9wY7Z5Xh0pE', 'thumbnail_url' => 'https://img.youtube.com/vi/9wY7Z5Xh0pE/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue Fever: What You Need to Know', 'description' => 'Key facts about dengue fever for public awareness.', 'youtube_url' => 'https://www.youtube.com/embed/5J3U2kC9q7U', 'thumbnail_url' => 'https://img.youtube.com/vi/5J3U2kC9q7U/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Home Remedies for Dengue Recovery', 'description' => 'Natural remedies to support recovery from dengue.', 'youtube_url' => 'https://www.youtube.com/embed/7gZ3vY5Qz0Q', 'thumbnail_url' => 'https://img.youtube.com/vi/7gZ3vY5Qz0Q/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue in Children: Symptoms and Care', 'description' => 'How to identify and care for dengue in children.', 'youtube_url' => 'https://www.youtube.com/embed/3sL6kLqW5kY', 'thumbnail_url' => 'https://img.youtube.com/vi/3sL6kLqW5kY/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue Vaccine: Facts and Updates', 'description' => 'Latest information on dengue vaccines.', 'youtube_url' => 'https://www.youtube.com/embed/1y2U6q0lV1g', 'thumbnail_url' => 'https://img.youtube.com/vi/1y2U6q0lV1g/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue Outbreaks: Causes and Control', 'description' => 'Why dengue outbreaks occur and how to control them.', 'youtube_url' => 'https://www.youtube.com/embed/6mW2kX9qZ0I', 'thumbnail_url' => 'https://img.youtube.com/vi/6mW2kX9qZ0I/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue and Climate Change', 'description' => 'How climate affects dengue transmission.', 'youtube_url' => 'https://www.youtube.com/embed/4tR3kW8fY7M', 'thumbnail_url' => 'https://img.youtube.com/vi/4tR3kW8fY7M/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Early Warning Signs of Dengue', 'description' => 'Recognize the early symptoms of dengue for timely treatment.', 'youtube_url' => 'https://www.youtube.com/embed/8vX5rJ3nL2U', 'thumbnail_url' => 'https://img.youtube.com/vi/8vX5rJ3nL2U/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue: Myths vs. Facts', 'description' => 'Debunking common myths about dengue fever.', 'youtube_url' => 'https://www.youtube.com/embed/2qT9mP8sZ5Q', 'thumbnail_url' => 'https://img.youtube.com/vi/2qT9mP8sZ5Q/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Community Efforts in Dengue Prevention', 'description' => 'How communities can work together to prevent dengue.', 'youtube_url' => 'https://www.youtube.com/embed/9yH7jK4uV3E', 'thumbnail_url' => 'https://img.youtube.com/vi/9yH7jK4uV3E/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Dengue Case Studies', 'description' => 'Real-life cases of dengue and lessons learned.', 'youtube_url' => 'https://www.youtube.com/embed/5kR2pW6qX8Y', 'thumbnail_url' => 'https://img.youtube.com/vi/5kR2pW6qX8Y/hqdefault.jpg', 'category' => 'Dengue'],
    ['title' => 'Basic First Aid Techniques', 'description' => 'Learn essential first aid skills for emergencies.', 'youtube_url' => 'https://www.youtube.com/embed/D9wQVI8n3Tc', 'thumbnail_url' => 'https://img.youtube.com/vi/D9wQVI8n3Tc/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Importance of Hand Hygiene', 'description' => 'Why washing hands is critical for health.', 'youtube_url' => 'https://www.youtube.com/embed/1uLQXkWMBHY', 'thumbnail_url' => 'https://img.youtube.com/vi/1uLQXkWMBHY/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Managing High Blood Pressure', 'description' => 'Tips to control hypertension effectively.', 'youtube_url' => 'https://www.youtube.com/embed/7g7zgQ3qKzE', 'thumbnail_url' => 'https://img.youtube.com/vi/7g7zgQ3qKzE/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Healthy Eating for Better Health', 'description' => 'How diet impacts overall wellness.', 'youtube_url' => 'https://www.youtube.com/embed/3m5mJ6ZyF0U', 'thumbnail_url' => 'https://img.youtube.com/vi/3m5mJ6ZyF0U/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Understanding Diabetes', 'description' => 'Causes, symptoms, and management of diabetes.', 'youtube_url' => 'https://www.youtube.com/embed/8q8q6zL2X0Q', 'thumbnail_url' => 'https://img.youtube.com/vi/8q8q6zL2X0Q/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Mental Health Awareness', 'description' => 'Importance of mental health and seeking help.', 'youtube_url' => 'https://www.youtube.com/embed/5zC6kY7qW0M', 'thumbnail_url' => 'https://img.youtube.com/vi/5zC6kY7qW0M/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Exercise for a Healthy Life', 'description' => 'Benefits of regular physical activity.', 'youtube_url' => 'https://www.youtube.com/embed/9vT5X2kZ3wY', 'thumbnail_url' => 'https://img.youtube.com/vi/9vT5X2kZ3wY/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Common Cold: Prevention and Care', 'description' => 'How to prevent and treat the common cold.', 'youtube_url' => 'https://www.youtube.com/embed/2rK7kW6qX8I', 'thumbnail_url' => 'https://img.youtube.com/vi/2rK7kW6qX8I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Heart Health Tips', 'description' => 'Ways to maintain a healthy heart.', 'youtube_url' => 'https://www.youtube.com/embed/6mW2kX9qZ0Q', 'thumbnail_url' => 'https://img.youtube.com/vi/6mW2kX9qZ0Q/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Importance of Vaccination', 'description' => 'Why vaccines are crucial for public health.', 'youtube_url' => 'https://www.youtube.com/embed/4tR3kW8fY7I', 'thumbnail_url' => 'https://img.youtube.com/vi/4tR3kW8fY7I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Dealing with Stress', 'description' => 'Strategies to manage stress effectively.', 'youtube_url' => 'https://www.youtube.com/embed/8vX5rJ3nL2I', 'thumbnail_url' => 'https://img.youtube.com/vi/8vX5rJ3nL2I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Sleep and Its Impact on Health', 'description' => 'How sleep affects your well-being.', 'youtube_url' => 'https://www.youtube.com/embed/2qT9mP8sZ5I', 'thumbnail_url' => 'https://img.youtube.com/vi/2qT9mP8sZ5I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Skin Care Basics', 'description' => 'Tips for maintaining healthy skin.', 'youtube_url' => 'https://www.youtube.com/embed/9yH7jK4uV3I', 'thumbnail_url' => 'https://img.youtube.com/vi/9yH7jK4uV3I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Hydration and Health', 'description' => 'Why staying hydrated is essential.', 'youtube_url' => 'https://www.youtube.com/embed/5kR2pW6qX8I', 'thumbnail_url' => 'https://img.youtube.com/vi/5kR2pW6qX8I/hqdefault.jpg', 'category' => 'General Health'],
    ['title' => 'Bone Health and Osteoporosis', 'description' => 'How to keep your bones strong.', 'youtube_url' => 'https://www.youtube.com/embed/0T_4eRZeV1Q', 'thumbnail_url' => 'https://img.youtube.com/vi/0T_4eRZeV1Q/hqdefault.jpg', 'category' => 'General Health']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Content - DengueCare</title>
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
        .search-filter {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
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
        .video-card {
            background: #ffffff;
            border: 1px solid #e6f0fa;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .video-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .video-card-body {
            padding: 15px;
        }
        .video-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #003087;
            margin-bottom: 10px;
        }
        .video-card-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }
        .video-card-category {
            font-size: 0.85rem;
            color: #005bb5;
            font-weight: 500;
        }
        .btn-primary {
            background: linear-gradient(90deg, #005bb5, #007bff);
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #003087, #005bb5);
            transform: scale(1.05);
        }
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }
        .modal-body iframe {
            width: 100%;
            height: 400px;
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
            .content-container h2 {
                font-size: 1.5rem;
            }
            .video-card img {
                height: 150px;
            }
            .modal-body iframe {
                height: 300px;
            }
        }
        @media (max-width: 576px) {
            .top-bar {
                display: none !important;
            }
            .navbar-brand h1 {
                font-size: 1.8rem;
            }
            .video-card-title {
                font-size: 1rem;
            }
            .video-card-description {
                font-size: 0.85rem;
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
                    <a href="edu_content.php" class="nav-item nav-link active">EDUCATION</a>
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
        <h2>Educational Videos</h2>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="search_input" placeholder="Search videos..." onkeyup="filterVideos()">
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="category_filter" onchange="filterVideos()">
                        <option value="">All Categories</option>
                        <option value="Dengue">Dengue</option>
                        <option value="General Health">General Health</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Video Grid -->
        <div class="row g-4" id="video_grid">
            <?php foreach ($videos as $video) { ?>
                <div class="col-lg-4 col-md-6 video-item" data-category="<?php echo htmlspecialchars($video['category']); ?>" data-title="<?php echo htmlspecialchars(strtolower($video['title'])); ?>">
                    <div class="video-card">
                        <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($video['title']); ?>" onerror="this.src='https://via.placeholder.com/320x200?text=Thumbnail+Unavailable';">
                        <div class="video-card-body">
                            <h5 class="video-card-title"><?php echo htmlspecialchars($video['title']); ?></h5>
                            <p class="video-card-description"><?php echo htmlspecialchars(substr($video['description'], 0, 100)) . '...'; ?></p>
                            <p class="video-card-category"><?php echo htmlspecialchars($video['category']); ?></p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal" onclick="loadVideo('<?php echo htmlspecialchars($video['youtube_url']); ?>', '<?php echo htmlspecialchars($video['title']); ?>')">
                                <i class="fas fa-play me-2"></i>Watch Video
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Video Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="videoFrame" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
        function filterVideos() {
            const searchInput = document.getElementById('search_input').value.toLowerCase();
            const categoryFilter = document.getElementById('category_filter').value;
            const videoItems = document.querySelectorAll('.video-item');

            videoItems.forEach(item => {
                const title = item.getAttribute('data-title');
                const category = item.getAttribute('data-category');
                const matchesSearch = !searchInput || title.includes(searchInput);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                item.style.display = matchesSearch && matchesCategory ? '' : 'none';
            });
        }

        function loadVideo(url, title) {
            const videoFrame = document.getElementById('videoFrame');
            const modalTitle = document.getElementById('videoModalLabel');
            videoFrame.src = url;
            modalTitle.textContent = title;
            console.log(`Loading video: ${url}`); // Debug log
        }

        // Clear iframe src when modal is closed to stop video playback
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoFrame').src = '';
        });
    </script>
</body>
</html>