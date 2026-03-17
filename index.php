<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masterminds Series | Management Portfolio</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --dark-bg: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-hover: rgba(255, 255, 255, 0.08);
            --border-color: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--dark-bg);
            color: #f8fafc;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-image: radial-gradient(circle at 50% 50%, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
        }

        .hero-section {
            padding: 80px 0 40px;
            text-align: center;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: #94a3b8;
            max-width: 700px;
            margin: 0 auto 3rem;
        }

        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px;
            max-width: 1300px;
            margin: 0 auto 80px;
        }

        .project-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            text-decoration: none;
            color: inherit;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .project-card:hover {
            transform: translateY(-8px);
            background: var(--card-hover);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .project-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--theme-color, #fff);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--theme-color, #fff);
        }

        .card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .launch-btn {
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            color: var(--theme-color, #fff);
            font-size: 0.9rem;
            transition: gap 0.3s;
        }

        .project-card:hover .launch-btn {
            gap: 12px;
        }

        .launch-btn i {
            margin-left: 8px;
        }

        /* Color Themes for Cards */
        .college { --theme-color: #0d6efd; }
        .livestock { --theme-color: #198754; }
        .attendance { --theme-color: #6c757d; }
        .employee { --theme-color: #6f42c1; }
        .event { --theme-color: #d63384; }
        .transport { --theme-color: #ffc107; }
        .library { --theme-color: #0a58ca; }
        .blood { --theme-color: #dc3545; }
        .billing { --theme-color: #20c997; }
        .media { --theme-color: #ffd700; }

        .footer {
            text-align: center;
            padding: 40px;
            color: #64748b;
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>

    <div class="hero-section container">
        <h1 class="hero-title">Masterminds Series</h1>
        <p class="hero-subtitle">A collection of 10 professional-grade Management Systems, each featuring unique aesthetics, secure authentication, and robust CRUD logic.</p>
    </div>

    <div class="project-grid">
        <!-- 01 College -->
        <a href="01_college_mgmt/login.php" class="project-card college">
            <div>
                <i class="fas fa-university card-icon"></i>
                <h3 class="card-title">College Admin</h3>
                <p class="card-description">Educational portal for managing students, departments, and academic data with a Navy Blue academic theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 02 Livestock -->
        <a href="02_livestock_mgmt/login.php" class="project-card livestock">
            <div>
                <i class="fas fa-paw card-icon"></i>
                <h3 class="card-title">Livestock Farm</h3>
                <p class="card-description">Inventory system for managing farm animals, health checks, and production metrics with a Green Farm theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 03 Student Attendance -->
        <a href="03_student_attendance/login.php" class="project-card attendance">
            <div>
                <i class="fas fa-calendar-check card-icon"></i>
                <h3 class="card-title">Attendance Node</h3>
                <p class="card-description">Modular attendance tracking system with date-based logic and status reporting in a sleek Slate Grey theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 04 Employee -->
        <a href="04_employee_mgmt/login.php" class="project-card employee">
            <div>
                <i class="fas fa-id-badge card-icon"></i>
                <h3 class="card-title">Employee Hub</h3>
                <p class="card-description">HR management system focusing on staff details, salary structures, and professional records in a Purple theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 05 Event -->
        <a href="05_event_mgmt/login.php" class="project-card event">
            <div>
                <i class="fas fa-ticket-alt card-icon"></i>
                <h3 class="card-title">Event Registry</h3>
                <p class="card-description">Planning system for corporate and social events with pass generation logic in a Vibrant Magenta theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 06 Transport -->
        <a href="06_transport_mgmt/login.php" class="project-card transport">
            <div>
                <i class="fas fa-bus card-icon"></i>
                <h3 class="card-title">Fleet Command</h3>
                <p class="card-description">Vehicle and route management system with fleet status indicators in a high-contrast Dark Grey and Yellow theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 07 Library -->
        <a href="07_library_mgmt/login.php" class="project-card library">
            <div>
                <i class="fas fa-book card-icon"></i>
                <h3 class="card-title">Lending Stack</h3>
                <p class="card-description">Book circulation and lending system featuring complex transactional issue/return logic in a Navy Blue/Cream theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 08 Blood Bank -->
        <a href="08_blood_bank/login.php" class="project-card blood">
            <div>
                <i class="fas fa-heartbeat card-icon"></i>
                <h3 class="card-title">Donor Registry</h3>
                <p class="card-description">Medical directory for life-saving donor data with city/group filtering in a Professional Red/White theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 09 Billing -->
        <a href="09_billing_shopping_system/login.php" class="project-card billing">
            <div>
                <i class="fas fa-shopping-cart card-icon"></i>
                <h3 class="card-title">RetailPro POS</h3>
                <p class="card-description">Billing and inventory system with real-time math calculation and atomic stock sync in a Teal/Dark Grey theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>

        <!-- 10 Media -->
        <a href="10_music_book_store/login.php" class="project-card media">
            <div>
                <i class="fas fa-music card-icon"></i>
                <h3 class="card-title">Infinity Media</h3>
                <p class="card-description">Premium media storefront featuring JOIN query logic and a card-based catalog UI in a Deep Purple/Gold theme.</p>
            </div>
            <div class="launch-btn">ENTER SYSTEM <i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    <footer class="footer">
        <p>&copy; 2026 Masterminds Series. All systems 100% verified and secure. | Built with &hearts; in AGENTIC mode.</p>
    </footer>

</body>
</html>
