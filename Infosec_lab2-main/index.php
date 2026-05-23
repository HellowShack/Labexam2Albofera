<?php
session_start();

// If already logged in, redirect to dashboard
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .landing-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .landing-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px;
            text-align: center;
            max-width: 700px;
        }

        .landing-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3em;
            color: white;
        }

        .landing-title {
            font-size: 2.5em;
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .landing-subtitle {
            font-size: 1.3em;
            color: #667eea;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .landing-description {
            color: #555;
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
            text-align: left;
        }

        .feature-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .feature-item h3 {
            color: #667eea;
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }

        .feature-item p {
            color: #666;
            margin: 0;
            font-size: 0.95em;
        }

        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .cta-button {
            padding: 16px 40px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .cta-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .cta-secondary {
            background: #f0f0f0;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .cta-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        .security-badge {
            margin-top: 40px;
            padding-top: 40px;
            border-top: 2px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 0.95em;
        }

        .security-badge strong {
            color: #27ae60;
            display: block;
            margin-bottom: 10px;
        }

        .badge-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .badge-item {
            font-size: 2em;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .landing-content {
                padding: 40px 20px;
            }

            .landing-title {
                font-size: 2em;
            }

            .landing-subtitle {
                font-size: 1.1em;
            }

            .landing-description {
                font-size: 1em;
            }

            .feature-list {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .cta-button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="landing-container">
    <div class="landing-content">
        <div class="landing-logo">📚</div>
        
        <h1 class="landing-title">Student Management System</h1>
        <h2 class="landing-subtitle">Secure. Efficient. Professional.</h2>
        
        <p class="landing-description">
            A modern, secure platform for managing student records with advanced security features, 
            role-based access control, and comprehensive audit logging.
        </p>

        <div class="feature-list">
            <div class="feature-item">
                <h3>🔒 Secure</h3>
                <p>Enterprise-grade encryption and authentication</p>
            </div>
            <div class="feature-item">
                <h3>⚡ Fast</h3>
                <p>Optimized database queries and performance</p>
            </div>
            <div class="feature-item">
                <h3>📊 Reliable</h3>
                <p>Normalized database with backup strategies</p>
            </div>
            <div class="feature-item">
                <h3>👥 User-Friendly</h3>
                <p>Intuitive interface with responsive design</p>
            </div>
        </div>

        <div class="cta-buttons">
            <a href="login.php" class="cta-button cta-primary">Login to Dashboard</a>
            <a href="#about" class="cta-button cta-secondary">Learn More</a>
        </div>

        <div class="security-badge">
            <strong>✓ Security Features Implemented</strong>
            Bcrypt Password Hashing • SQL Injection Prevention • XSS Protection • 
            Input Validation • Session Security • Role-Based Access Control • 
            Database Normalization • Audit Logging
        </div>
    </div>
</div>

</body>
</html>
