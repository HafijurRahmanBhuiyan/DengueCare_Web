<?php
// Start the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// No redirect, proceed to display the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - DengueCare</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
        }
        .logout-container {
            text-align: center;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .logout-container h2 {
            color: #003087;
            margin-bottom: 20px;
        }
        .logout-container p {
            color: #666;
            font-size: 1.1rem;
        }
        .logout-container a {
            color: #005bb5;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-container a:hover {
            text-decoration: underline;
        }
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #e6f0fa;
            border-top: 5px solid #003087;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
            display: block;
        }
        .checkmark {
            display: none;
            font-size: 50px;
            color: #28a745;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loaded .loader {
            display: none;
        }
        .loaded .checkmark {
            display: block;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="loader"></div>
        <div class="checkmark">✔</div>
        <h2>Logged Out</h2>
        <p>You have been logged out successfully.</p>
        <p><a href="../index.php">Return to Homepage</a></p>
    </div>

    <script>
        // Show checkmark and hide loader after 2 seconds
        setTimeout(() => {
            document.querySelector('.logout-container').classList.add('loaded');
        }, 2000);
    </script>
</body>
</html>