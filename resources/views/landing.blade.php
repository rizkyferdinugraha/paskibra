<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PASKIBRA SMK - Sistem Manajemen Organisasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --bg-light: #ffffff;
            --bg-dark: #0f172a;
            --text-light: #1e293b;
            --text-dark: #e2e8f0;
            --card-bg-light: #f8fafc;
            --card-bg-dark: #1e293b;
            --shadow-light: rgba(0, 0, 0, 0.1);
            --shadow-dark: rgba(0, 0, 0, 0.3);
            --border-light: #e2e8f0;
            --border-dark: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-light);
            transition: background-color 0.3s ease, color 0.3s ease;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body.dark-mode {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        body.dark-mode header {
            background: rgba(15, 23, 42, 0.95);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: 'Poppins', sans-serif;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        body.dark-mode .nav-links a {
            color: var(--text-dark);
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .dark-mode-toggle {
            background: transparent;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        body.dark-mode .dark-mode-toggle {
            color: var(--text-dark);
        }

        /* Hero Section */
        .hero {
            margin-top: 80px;
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(-60px, -60px);
            }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
            animation: fadeInUp 0.8s ease;
        }

        .hero p {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            border-radius: 50px;
        }

        .btn-hero-primary {
            background: white;
            color: #667eea;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(10px);
        }

        .btn-hero-secondary:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #64748b;
            margin-bottom: 4rem;
        }

        body.dark-mode .section-subtitle {
            color: #94a3b8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--card-bg-light);
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        body.dark-mode .feature-card {
            background: var(--card-bg-dark);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.6;
        }

        body.dark-mode .feature-card p {
            color: #94a3b8;
        }

        /* Statistics Section */
        .statistics {
            padding: 4rem 2rem;
            background: var(--primary-gradient);
            position: relative;
            overflow: hidden;
        }

        .statistics::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,%3Csvg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath d="M50 30c11.046 0 20 8.954 20 20s-8.954 20-20 20-20-8.954-20-20 8.954-20 20-20zm0 10c-5.523 0-10 4.477-10 10s4.477 10 10 10 10-4.477 10-10-4.477-10-10-10z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* How It Works Section */
        .how-it-works {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }

        .step h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .step p {
            color: #64748b;
            line-height: 1.6;
        }

        body.dark-mode .step p {
            color: #94a3b8;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 6rem 2rem;
            background: var(--card-bg-light);
        }

        body.dark-mode .testimonials {
            background: var(--card-bg-dark);
        }

        .testimonials-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .testimonial-card {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        body.dark-mode .testimonial-card {
            background: var(--bg-dark);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .testimonial-text {
            font-style: italic;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: #475569;
        }

        body.dark-mode .testimonial-text {
            color: #cbd5e1;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .author-info h4 {
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }

        .author-info p {
            font-size: 0.9rem;
            color: #64748b;
        }

        body.dark-mode .author-info p {
            color: #94a3b8;
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
        }

        .cta-content p {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            background: var(--card-bg-light);
            padding: 4rem 2rem 2rem;
            border-top: 1px solid var(--border-light);
        }

        body.dark-mode footer {
            background: var(--card-bg-dark);
            border-top-color: var(--border-dark);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        body.dark-mode .footer-section a {
            color: #94a3b8;
        }

        .footer-section a:hover {
            color: #667eea;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid var(--border-light);
            color: #64748b;
        }

        body.dark-mode .footer-bottom {
            border-top-color: var(--border-dark);
            color: #94a3b8;
        }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }

        body.dark-mode .mobile-menu-toggle {
            color: var(--text-dark);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-light);
                flex-direction: column;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            body.dark-mode .nav-links {
                background: var(--bg-dark);
            }

            .nav-links.active {
                display: flex;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero {
                width: 100%;
                max-width: 300px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .steps-container {
                grid-template-columns: 1fr;
            }

            .testimonials-container {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
            }
        }

        /* Loading Animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">PASKIBRA SMK</div>
            <div class="nav-links" id="navLinks">
                <a href="#home">Beranda</a>
                <a href="#features">Fitur</a>
                <a href="#how-it-works">Cara Kerja</a>
                <a href="#testimonials">Testimoni</a>
                <a href="#contact">Kontak</a>
            </div>
            <div class="nav-buttons">
                @guest
                    <a class="btn btn-outline" href="{{ route('login') }}">Masuk</a>
                    <a class="btn btn-primary" href="{{ route('register') }}">Daftar</a>
                @endguest
                @auth
                    <a class="btn btn-outline" href="{{ route('dashboard') }}">Dashboard</a>
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary">Keluar</button>
                    </form>
                @endauth
                <button class="dark-mode-toggle" id="darkModeToggle" aria-label="Toggle Dark Mode">
                    <i class="fas fa-moon"></i>
                </button>
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Sistem Manajemen PASKIBRA SMK</h1>
            <p>Manajemen organisasi Paskibra: anggota, acara, kas, dan komplain — cepat dan terpusat</p>
            <div class="hero-buttons">
                <a class="btn btn-hero btn-hero-primary" href="{{ route('complaints.create') }}" onclick="showLoader('Membuka form komplain...')">
                    <i class="fas fa-comment-dots"></i> Ajukan Komplain
                </a>
                @guest
                    <a class="btn btn-hero btn-hero-secondary" href="{{ route('login') }}" onclick="showLoader('Membuka halaman login...')">
                        <i class="fas fa-tachometer-alt"></i> Masuk ke Dashboard
                    </a>
                @endguest
                @auth
                    <a class="btn btn-hero btn-hero-secondary" href="{{ route('dashboard') }}" onclick="showLoader('Membuka dashboard...')">
                        <i class="fas fa-tachometer-alt"></i> Buka Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Kelola organisasi Paskibra dengan mudah dan efisien</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Data Anggota</h3>
                <p>Status keanggotaan, kartu anggota, dan profil terkelola dengan sistem yang terintegrasi dan mudah
                    diakses</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Acara & Absensi</h3>
                <p>Kelola agenda, absen, penilaian, dan dokumentasi kegiatan dengan sistem yang terorganisir</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Kas Organisasi</h3>
                <p>Pencatatan pemasukan dan pengeluaran transparan dengan laporan keuangan yang detail</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Komplain Tertangani</h3>
                <p>Saluran komplain publik dengan tindak lanjut terstruktur untuk meningkatkan kualitas layanan</p>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Acara Terkelola</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Komplain Tertangani</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Dukungan Sistem</div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <h2 class="section-title">Cara Kerja</h2>
        <p class="section-subtitle">Tiga langkah mudah untuk memulai</p>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Daftar Akun</h3>
                <p>Buat akun dengan data diri lengkap dan tunggu verifikasi dari admin untuk mengakses sistem</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Akses Dashboard</h3>
                <p>Login ke dashboard dan mulai kelola data anggota, acara, kas, dan komplain dengan mudah</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Pantau & Lapor</h3>
                <p>Pantau perkembangan organisasi melalui laporan real-time dan grafik analitik yang informatif</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials" id="testimonials">
        <h2 class="section-title">Apa Kata Mereka</h2>
        <p class="section-subtitle">Testimoni dari pengguna sistem kami</p>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <p class="testimonial-text">"Sistem ini sangat membantu dalam mengelola data anggota dan acara.
                    Interface yang user-friendly membuat pekerjaan kami jadi lebih efisien."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">AS</div>
                    <div class="author-info">
                        <h4>Ahmad Santoso</h4>
                        <p>Ketua PASKIBRA</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">"Fitur kas organisasi sangat transparan dan memudahkan kami dalam membuat
                    laporan keuangan. Tidak ada lagi kesalahan pencatatan."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">RP</div>
                    <div class="author-info">
                        <h4>Rina Pratiwi</h4>
                        <p>Bendahara</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-text">"Sistem komplain yang responsif membuat komunikasi dengan anggota jadi
                    lebih baik. Semua masukan dapat ditindaklanjuti dengan cepat."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">BW</div>
                    <div class="author-info">
                        <h4>Budi Wijaya</h4>
                        <p>Sekretaris</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="cta">
        <div class="cta-content">
            <h2>Siap Memulai Mengelola PASKIBRA Lebih Baik?</h2>
            <p>Daftar sekarang dan nikmati kemudahan dalam mengelola anggota, acara, kas, dan komplain dalam satu
                sistem.</p>
            @guest
                <a class="btn btn-hero btn-hero-primary" href="{{ route('register') }}" onclick="showLoader('Membuka halaman pendaftaran...')">
                    <i class="fas fa-user-plus"></i> Buat Akun Gratis
                </a>
            @endguest
            @auth
                <a class="btn btn-hero btn-hero-primary" href="{{ route('dashboard') }}" onclick="showLoader('Membuka dashboard...')">
                    <i class="fas fa-tachometer-alt"></i> Masuk Dashboard
                </a>
            @endauth
        </div>
    </section>

    <!-- Contact Section -->
    <section class="features" id="contact">
        <h2 class="section-title">Kontak</h2>
        <p class="section-subtitle">Hubungi kami untuk pertanyaan atau dukungan</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-envelope"></i></div>
                <h3>Email</h3>
                <p>paskibra@sekolah.sch.id</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-phone"></i></div>
                <h3>Telepon</h3>
                <p>+62 812-3456-7890</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-location-dot"></i></div>
                <h3>Alamat</h3>
                <p>SMK Contoh, Jl. Pendidikan No. 123, Kota</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Tentang</h3>
                <p>Sistem Manajemen PASKIBRA SMK untuk mempermudah administrasi dan kegiatan organisasi.</p>
            </div>
            <div class="footer-section">
                <h3>Tautan</h3>
                <ul>
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#testimonials">Testimoni</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Aksi Cepat</h3>
                <ul>
                    <li><a href="{{ route('complaints.create') }}">Ajukan Komplain</a></li>
                    @guest
                        <li><a href="{{ route('login') }}">Masuk</a></li>
                        <li><a href="{{ route('register') }}">Daftar</a></li>
                    @endguest
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} PASKIBRA SMK. All rights reserved.
        </div>
    </footer>

    <!-- Loader Component -->
    <x-loader />

    <script>
        // Dark mode persist and toggle
        (function() {
            const body = document.body;
            const toggle = document.getElementById('darkModeToggle');
            const stored = localStorage.getItem('paskibra-theme');
            if (stored === 'dark') body.classList.add('dark-mode');
            toggle && toggle.addEventListener('click', function() {
                body.classList.toggle('dark-mode');
                localStorage.setItem('paskibra-theme', body.classList.contains('dark-mode') ? 'dark' : 'light');
            });
        })();

        // Mobile menu toggle
        (function() {
            const btn = document.getElementById('mobileMenuToggle');
            const links = document.getElementById('navLinks');
            btn && btn.addEventListener('click', function() {
                links.classList.toggle('active');
            });
        })();

        // Smooth scroll for internal links
        (function() {
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId && targetId.length > 1) {
                        e.preventDefault();
                        document.querySelector(targetId)?.scrollIntoView({
                            behavior: 'smooth'
                        });
                        history.pushState(null, '', targetId);
                    }
                });
            });
        })();
    </script>
</body>

</html>
