<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bennito254 – Software Developer</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- AOS Animation CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --sky-blue: #00bfff;
            --light-blue: #e6f7ff;
            --dark-blue: #007acc;
            --text-color: #000;
            --bg-color: #fff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', sans-serif;
            scroll-behavior: smooth;
        }

        .dark-mode {
            --light-blue: #1a1a1a;
            --dark-blue: #004d61;
            --sky-blue: #00aaff;
            --text-color: #f0f0f0;
            --bg-color: #121212;
        }

        .navbar {
            background-color: var(--sky-blue);
        }

        .navbar-brand,
        .nav-link {
            color: var(--text-color) !important;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold;
        }

        .sticky-top {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1030;
        }

        .hero {
            background: url('https://images.pexels.com/photos/1181244/pexels-photo-1181244.jpeg') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 120px 0;
            text-align: center;
        }

        .section-title {
            color: var(--dark-blue);
            margin-bottom: 30px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 191, 255, 0.15);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .btn-primary {
            background-color: var(--sky-blue);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--dark-blue);
        }

        footer {
            background-color: var(--sky-blue);
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        .project-links {
            margin-top: 15px;
        }

        .project-links a {
            margin-right: 10px;
            text-decoration: none;
            color: var(--dark-blue);
            font-weight: 500;
        }

        .project-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Floating Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Bennito254</a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span><i class="fas fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-user me-1"></i>About</a></li>
                <li class="nav-item"><a class="nav-link" href="#skills"><i class="fas fa-code me-1"></i>Skills</a></li>
                <li class="nav-item"><a class="nav-link" href="#projects"><i class="fas fa-folder-open me-1"></i>Projects</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact"><i class="fas fa-envelope me-1"></i>Contact</a></li>
                <li class="nav-item">
                    <button id="theme-toggle" class="btn btn-sm btn-light ms-3"><i class="fas fa-adjust"></i></button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero" id="about">
    <div class="container" data-aos="fade-up">
        <h1 class="display-4 fw-bold">Hi, I'm Bennito254</h1>
        <p class="lead">A Software & Web Developer from Kenya</p>
        <p><i class="fas fa-briefcase me-1"></i>8+ years of experience • <i class="fas fa-laptop-code me-1"></i>Freelancer • <i class="fas fa-check-circle me-1"></i>Available</p>
        <a href="#contact" class="btn btn-light mt-3"><i class="fas fa-paper-plane me-1"></i>Hire Me</a>
    </div>
</section>

<!-- Skills Section -->
<section class="py-5" id="skills">
    <div class="container">
        <h2 class="text-center section-title" data-aos="fade-up">Skills</h2>
        <div class="row text-center">
            <div class="col-md-2 offset-md-1 mb-3" data-aos="zoom-in"><div class="card p-3"><i class="fab fa-php fa-2x mb-2"></i><div>PHP</div></div></div>
            <div class="col-md-2 mb-3" data-aos="zoom-in"><div class="card p-3"><i class="fab fa-node-js fa-2x mb-2"></i><div>NodeJS</div></div></div>
            <div class="col-md-2 mb-3" data-aos="zoom-in"><div class="card p-3"><i class="fas fa-bolt fa-2x mb-2"></i><div>NextJS</div></div></div>
            <div class="col-md-2 mb-3" data-aos="zoom-in"><div class="card p-3"><i class="fab fa-laravel fa-2x mb-2"></i><div>Laravel</div></div></div>
            <div class="col-md-2 mb-3" data-aos="zoom-in"><div class="card p-3"><i class="fas fa-code fa-2x mb-2"></i><div>CodeIgniter</div></div></div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section class="py-5 bg-white" id="projects">
    <div class="container">
        <h2 class="text-center section-title" data-aos="fade-up">Projects</h2>
        <div class="row">
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card h-100 p-3">
                    <img src="https://images.pexels.com/photos/669619/pexels-photo-669619.jpeg" class="card-img-top mb-2" alt="M-Pesa">
                    <h5 class="card-title"><i class="fas fa-money-check-alt me-2"></i>M-Pesa Integrations</h5>
                    <p class="card-text">Secure and scalable integrations with Safaricom's M-Pesa API for e-commerce, billing, and apps.</p>
                    <div class="project-links">
                        <a href="https://github.com/bennito254/mpesa-integration" target="_blank"><i class="fab fa-github me-1"></i>GitHub</a>
                        <a href="https://example.com/mpesa-live" target="_blank"><i class="fas fa-globe me-1"></i>Live Demo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 p-3">
                    <img src="https://images.pexels.com/photos/1342460/pexels-photo-1342460.jpeg" class="card-img-top mb-2" alt="SMS">
                    <h5 class="card-title"><i class="fas fa-sms me-2"></i>SMS Integration</h5>
                    <p class="card-text">Robust SMS gateway integrations for OTPs, alerts, and marketing campaigns using local and global APIs.</p>
                    <div class="project-links">
                        <a href="https://github.com/bennito254/sms-gateway" target="_blank"><i class="fab fa-github me-1"></i>GitHub</a>
                        <a href="https://example.com/sms-live" target="_blank"><i class="fas fa-globe me-1"></i>Live Demo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 p-3">
                    <img src="https://images.pexels.com/photos/3183181/pexels-photo-3183181.jpeg" class="card-img-top mb-2" alt="HRM">
                    <h5 class="card-title"><i class="fas fa-users-cog me-2"></i>HRM Systems</h5>
                    <p class="card-text">Custom-built Human Resource Management systems tailored for local and enterprise use cases.</p>
                    <div class="project-links">
                        <a href="https://github.com/bennito254/hrm-system" target="_blank"><i class="fab fa-github me-1"></i>GitHub</a>
                        <a href="https://example.com/hrm-live" target="_blank"><i class="fas fa-globe me-1"></i>Live Demo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact / CTA Section -->
<section class="py-5" id="contact">
    <div class="container text-center" data-aos="fade-up">
        <h2 class="section-title">Let’s Work Together</h2>
        <p class="mb-4">Have a project or task in mind? I’m open for freelance work and long-term collaboration.</p>
        <a href="mailto:youremail@example.com" class="btn btn-primary btn-lg"><i class="fas fa-envelope me-2"></i>Contact Me</a>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Bennito254. All rights reserved.</p>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();

    document.getElementById('theme-toggle').addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });
</script>
</body>
</html>
