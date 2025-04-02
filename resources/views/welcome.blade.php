<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Grading System - Streamline Your Academic Assessment</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'instrument-sans', sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                background-color: #f8fafc;
            }

            .nav-container {
                background: rgba(244, 246, 246, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid rgba(170, 183, 184, 0.2);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .hero-section {
                position: relative;
                height: 100vh;
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
            }

            .hero-content {
                position: relative;
                z-index: 10;
                text-align: center;
                max-width: 900px;
                padding: 0 20px;
            }

            .hero-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #1C2833;
                line-height: 1.2;
                margin-bottom: 1.5rem;
            }

            @media (min-width: 768px) {
                .hero-title {
                    font-size: 4rem;
                }
            }

            .hero-subtitle {
                font-size: 1.1rem;
                color: #2E4053;
                max-width: 600px;
                margin: 0 auto 2.5rem;
                line-height: 1.6;
            }

            @media (min-width: 768px) {
                .hero-subtitle {
                    font-size: 1.25rem;
                }
            }

            .btn-primary {
                background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
                border: none;
                padding: 0.75rem 2rem;
                font-weight: 600;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                display: inline-block;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            .btn-secondary {
                background: #F4F6F6;
                color: #1C2833;
                border: 2px solid #AAB7B8;
                padding: 0.75rem 2rem;
                font-weight: 600;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                display: inline-block;
            }

            .btn-secondary:hover {
                background: #D5DBDB;
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .features-section {
                padding: 80px 0;
                background: #fff;
            }

            .feature-card {
                background: white;
                border-radius: 1rem;
                padding: 2rem;
                text-align: center;
                transition: all 0.3s ease;
                border: 1px solid rgba(0, 0, 0, 0.05);
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                color: white;
                font-size: 1.5rem;
            }

            .feature-title {
                font-size: 1.25rem;
                font-weight: 600;
                color: #1C2833;
                margin-bottom: 1rem;
            }

            .feature-description {
                color: #2E4053;
                line-height: 1.6;
            }

            .cta-section {
                padding: 80px 0;
                text-align: center;
                background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
            }

            .footer {
                background: #1C2833;
                color: #F4F6F6;
            }

            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }

            .floating-icon {
                animation: float 6s ease-in-out infinite;
            }

            /* Carousel Styles */
            .carousel {
                position: relative;
                overflow: hidden;
            }

            .carousel-inner {
                display: flex;
                transition: transform 0.5s ease;
            }

            .carousel-item {
                min-width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .carousel-controls {
                position: absolute;
                bottom: 20px;
                left: 0;
                right: 0;
                display: flex;
                justify-content: center;
                gap: 8px;
            }

            .carousel-indicator {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.5);
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .carousel-indicator.active {
                background-color: #667eea;
            }

            .carousel-prev, .carousel-next {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.8);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 10;
                transition: all 0.3s ease;
                color: #333;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .carousel-prev:hover, .carousel-next:hover {
                background: #fff;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .carousel-prev {
                left: 20px;
            }

            .carousel-next {
                right: 20px;
            }

            .testimonial-card {
                background: #fff;
                border-radius: 1rem;
                padding: 2rem;
                text-align: center;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                max-width: 500px;
                margin: 0 auto;
            }

            .stats-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                padding: 40px 0;
            }

            .stat-item {
                text-align: center;
                padding: 20px;
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 10px;
            }

            .stat-label {
                color: #2E4053;
                font-weight: 500;
            }

            /* Background blobs */
            .bg-blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(60px);
                z-index: 1;
                opacity: 0.5;
            }

            .bg-blob-1 {
                width: 300px;
                height: 300px;
                background: rgba(28, 40, 51, 0.3);
                top: 20%;
                left: 10%;
            }

            .bg-blob-2 {
                width: 350px;
                height: 350px;
                background: rgba(46, 64, 83, 0.3);
                bottom: 10%;
                right: 15%;
            }

            /* Avatar styling */
            @keyframes shine {
                0% {
                    transform: translateX(-100%) translateY(-100%) rotate(-45deg);
                }
                100% {
                    transform: translateX(100%) translateY(100%) rotate(-45deg);
                }
            }

            .avatar-shine {
                animation: shine 5s ease-in-out infinite;
                position: absolute;
                top: -100%;
                left: -100%;
                right: 0;
                bottom: 0;
                width: 150%;
                height: 150%;
                transform: rotate(-45deg);
                background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            }

            /* Hero content gradient text */
            .bg-clip-text.text-transparent.bg-gradient-to-r {
                background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-weight: 700;
            }

            /* Benefits Section styling */
            .cta-section {
                padding: 80px 0;
                text-align: center;
                background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
            }

            .cta-section .text-[#667eea] {
                color: #1C2833;
            }

            .cta-section .bg-white {
                background: rgba(244, 246, 246, 0.95);
                border: 1px solid rgba(170, 183, 184, 0.2);
            }

            .cta-section h2 {
                color: #1C2833;
            }

            .cta-section p {
                color: #2E4053;
            }

            .cta-section .text-gray-800 {
                color: #1C2833;
            }

            .cta-section .text-gray-600 {
                color: #2E4053;
            }

            /* Developer section styling */
            #developer {
                background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
            }

            #developer .bg-white {
                background: rgba(244, 246, 246, 0.95);
                border: 1px solid rgba(170, 183, 184, 0.2);
            }

            #developer .text-gray-800 {
                color: #1C2833;
            }

            #developer .text-gray-600 {
                color: #2E4053;
            }

            #developer .bg-gray-50 {
                background: rgba(244, 246, 246, 0.95);
                border: 1px solid rgba(170, 183, 184, 0.2);
            }

            #developer .text-indigo-600 {
                color: #1C2833;
            }

            #developer .bg-gradient-to-br {
                background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
            }

            /* Footer styling update */
            .footer {
                background: #1C2833;
                color: #F4F6F6;
            }

            .footer .text-gray-400 {
                color: #AAB7B8;
            }

            .footer .border-gray-700 {
                border-color: #2E4053;
            }

            /* Update hover states */
            .hover\:shadow-md:hover {
                box-shadow: 0 4px 6px -1px rgba(28, 40, 51, 0.1), 0 2px 4px -1px rgba(28, 40, 51, 0.06);
            }

            .hover\:text-gray-900:hover {
                color: #1C2833;
            }

            /* Update the navigation text colors */
            .text-gray-700 {
                color: #2E4053;
            }

            .text-gray-800 {
                color: #1C2833;
            }

            /* Update social media icons */
            .text-gray-700.hover\:text-indigo-600:hover {
                color: #1C2833;
            }

            .bg-gray-100 {
                background: rgba(244, 246, 246, 0.95);
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="nav-container fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <span class="text-xl font-semibold text-gray-800">Grading System</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="#features" class="text-gray-700 hover:text-gray-900">Features</a>
                        <a href="#benefits" class="text-gray-700 hover:text-gray-900">Benefits</a>
                        <a href="#developer" class="text-gray-700 hover:text-gray-900">Developer</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-white ml-4">
                                Log in
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary ml-4">
                                Log in
                            </a>
                        @endauth
                    </div>
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-white py-2 px-4">
                <a href="#features" class="block py-2 text-gray-700">Features</a>
                <a href="#benefits" class="block py-2 text-gray-700">Benefits</a>
                <a href="#developer" class="block py-2 text-gray-700">Developer</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="block py-2 text-indigo-600 font-medium">
                        Log in
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700">
                        Log in
                    </a>
                @endauth
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="bg-blob bg-blob-1"></div>
            <div class="bg-blob bg-blob-2"></div>
            <div class="hero-content">
                <div class="floating-icon mx-auto mb-8" style="width: 80px; height: 80px;">
                    <div class="feature-icon w-full h-full">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <h1 class="hero-title">
                    Streamline Your<br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r">
                        Grading Process
                    </span>
                </h1>
                <p class="hero-subtitle">
                    A comprehensive grading system designed to make academic assessment efficient, accurate, and user-friendly.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="btn-primary text-white">
                        Get Started
                    </a>
                    <a href="#features" class="btn-secondary">
                        Learn More
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-number">50%</div>
                        <div class="stat-label">Time Saved</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">3+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">98%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support Available</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="features-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Features that Empower</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Everything you need to manage grades efficiently and effectively</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h3 class="feature-title">Easy Grade Entry</h3>
                            <p class="feature-description">
                                Intuitive interface for quick and accurate grade input with smart validation and bulk operations.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="feature-title">Analytics & Reports</h3>
                            <p class="feature-description">
                                Comprehensive analytics and detailed reports for better decision-making and student progress tracking.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <h3 class="feature-title">Real-time Updates</h3>
                            <p class="feature-description">
                                Instant grade updates and notifications for students and teachers to stay informed.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="feature-title">Secure & Private</h3>
                            <p class="feature-description">
                                Enterprise-grade security measures to protect sensitive student data and academic records.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 5 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="feature-title">Mobile Friendly</h3>
                            <p class="feature-description">
                                Access grades and reports from any device, anywhere, anytime with our responsive design.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 6 -->
                    <div class="feature-card">
                        <div>
                            <div class="feature-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h3 class="feature-title">Customizable</h3>
                            <p class="feature-description">
                                Tailor the grading system to match your specific requirements and institutional policies.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="cta-section">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose Our Grading System?</h2>
                <p class="text-gray-600 mb-12 max-w-2xl mx-auto">
                    Experience the benefits of a modern, efficient, and user-friendly grading system
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-[#1C2833] text-3xl mb-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Save Time</h3>
                        <p>
                            Reduce grading time by up to 50% with our streamlined process and automated calculations.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-[#1C2833] text-3xl mb-4">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Secure & Reliable</h3>
                        <p>
                            Your data is protected with enterprise-grade security and regular backups.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                        <div class="text-[#1C2833] text-3xl mb-4">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Data Insights</h3>
                        <p>
                            Get valuable insights into student performance with advanced analytics tools.
                        </p>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('login') }}" class="btn-primary text-white inline-flex items-center">
                        <span>Start Using Our System</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Developer Details Section -->
        <section id="developer" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Developer Information</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Learn about the developer and the technology behind this grading system</p>
                </div>

                <div class="max-w-6xl mx-auto">
                    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
                        <div class="flex flex-col md:flex-row gap-10">
                            <div class="md:w-1/3">
                                <div class="sticky top-24">
                                    <div class="flex flex-col items-center">
                                        <div class="relative w-40 h-40 overflow-hidden rounded-full shadow-lg border-4 border-white mb-6">
                                            <!-- Gradient background with improved colors -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600"></div>
                                            
                                            <!-- Subtle pattern overlay -->
                                            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'1.5\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'1.5\'/%3E%3C/g%3E%3C/svg%3E');"></div>
                                            
                                            <!-- Initials with improved styling -->
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-white text-5xl font-bold tracking-wider" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">VJ</span>
                                            </div>
                                            
                                            <!-- Shine effect -->
                                            <div class="avatar-shine"></div>
                                            
                                            <!-- Subtle inner border -->
                                            <div class="absolute inset-0 rounded-full border border-white opacity-30"></div>
                                        </div>
                                        
                                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Vincent Jalalon</h3>
                                        <p class="text-indigo-600 font-medium mb-4">Lead Developer</p>
                                        
                                        <div class="flex justify-center space-x-4 mb-6">
                                            <a href="https://www.facebook.com/vivinz11" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors p-2 bg-gray-100 rounded-full">
                                                <i class="fab fa-facebook text-xl"></i>
                                            </a>
                                            <a href="https://github.com/jalalon11" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors p-2 bg-gray-100 rounded-full">
                                                <i class="fab fa-github text-xl"></i>
                                            </a>
                                            <a href="https://www.linkedin.com/in/vincent-jhanrey-jalalon-184299347/" target="_blank" class="text-gray-700 hover:text-indigo-600 transition-colors p-2 bg-gray-100 rounded-full">
                                                <i class="fab fa-linkedin-in text-xl"></i>
                                            </a>
                                            <a href="vinz0799@gmail.com" class="text-gray-400 hover:text-white transition-colors flex items-center mt-1">
                                                <i class="fas fa-envelope text-xl"></i>
                                            </a>
                                        </div>
                                        
                                        <div class="w-full p-4 bg-gray-50 rounded-lg mb-6">
                                            <h4 class="font-semibold text-gray-800 mb-2">Contact Information</h4>
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-envelope text-indigo-600 mr-2"></i>
                                                <span class="text-gray-600">vinz0799@gmail.com</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                                                <span class="text-gray-600">Manticao, Misamis Oriental, Philippines</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="md:w-2/3">
                                <div class="mb-8">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-user-circle text-indigo-600 mr-2"></i>
                                        About the Developer
                                    </h4>
                                    <div class="text-gray-600 space-y-4">
                                        <p>
                                            Vincent Jalalon is a dedicated full-stack developer with over 2 years of experience in building technology solutions. 
                                            Vincent brings a unique perspective to developing tools that enhance 
                                            the grading system experience.
                                        </p>
                                        <p>
                                            After experiencing firsthand the challenges educators face with outdated grading systems, Vincent embarked on developing this 
                                            comprehensive solution designed to streamline the grading process and provide valuable insights into student performance.
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="mb-8">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-laptop-code text-indigo-600 mr-2"></i>
                                        Technical Expertise
                                    </h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">Backend Development</div>
                                            <div class="text-sm text-gray-600">PHP, Laravel, MySQL</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">Frontend Development</div>
                                            <div class="text-sm text-gray-600">HTML, CSS, JavaScript, Tailwind</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">Technical Skills</div>
                                            <div class="text-sm text-gray-600">System Troubleshooting, AI Prompt Engineering</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">Database Design</div>
                                            <div class="text-sm text-gray-600">SQL, Database Optimization</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">UI/UX Design</div>
                                            <div class="text-sm text-gray-600">User-Centered Design, Wireframing</div>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <div class="font-medium text-gray-800">API Development</div>
                                            <div class="text-sm text-gray-600">RESTful APIs, API Integration</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-8">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-project-diagram text-indigo-600 mr-2"></i>
                                        Project Information
                                    </h4>
                                    <div class="space-y-4 text-gray-600">
                                        <p>
                                            This grading system is built using the Laravel framework with a focus on performance, security, and user experience. 
                                            The application leverages modern web technologies to provide a responsive interface that works seamlessly across devices.
                                        </p>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <h5 class="font-medium text-gray-800 mb-2">Technology Stack</h5>
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Backend: Laravel 12 (PHP 8.2.4)</li>
                                                <li>Frontend: Blade templates, Tailwind CSS, Bootstrap 5, Alpine.js</li>
                                                <li>Database: MySQL 8.0</li>
                                                <li>Authentication: Laravel Cloud Authentication</li>
                                                <li>Hosting: Laravel Cloud</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-code-branch text-indigo-600 mr-2"></i>
                                        Development Roadmap
                                    </h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start">
                                            <div class="w-5 h-5 rounded-full bg-green-500 mt-1 mr-3 flex-shrink-0"></div>
                                            <div>
                                                <h5 class="font-medium text-gray-800">Phase 1: Core Functionality (Completed)</h5>
                                                <p class="text-gray-600">Basic grading system with user management and simple reporting</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="w-5 h-5 rounded-full bg-yellow-500 mt-1 mr-3 flex-shrink-0"></div>
                                            <div>
                                                <h5 class="font-medium text-gray-800">Phase 2: Advanced Analytics (In Progress)</h5>
                                                <p class="text-gray-600">Enhanced reporting, data visualization, and performance tracking</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="w-5 h-5 rounded-full bg-gray-300 mt-1 mr-3 flex-shrink-0"></div>
                                            <div>
                                                <h5 class="font-medium text-gray-800">Phase 3: API & Integrations (Planned)</h5>
                                                <p class="text-gray-600">Public API for third-party integrations and mobile app development</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="w-5 h-5 rounded-full bg-gray-300 mt-1 mr-3 flex-shrink-0"></div>
                                            <div>
                                                <h5 class="font-medium text-gray-800">Phase 4: AI-Powered Insights (Planned)</h5>
                                                <p class="text-gray-600">Machine learning features for predictive analytics and personalized recommendations</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Grading System</h3>
                        <p class="text-gray-400">
                            Transforming how educational institutions manage and analyze student performance.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Home</a></li>
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a></li>
                            <li><a href="#benefits" class="text-gray-400 hover:text-white transition-colors">Benefits</a></li>
                            <li><a href="#developer" class="text-gray-400 hover:text-white transition-colors">Developer</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Development Status</h4>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                            <p class="text-gray-400">System in development</p>
                        </div>
                        <p class="text-gray-400 mt-2">Version: 1.0.0 Beta</p>
                        <div class="mt-4">
                            <p class="text-gray-400">Have suggestions?</p>
                            <a href="vinz0799@gmail.com" class="text-gray-400 hover:text-white transition-colors flex items-center mt-1">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send feedback
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                    <p>System in development. &copy; {{ date('Y') }} Developed by Vincent Jalalon.</p>
                </div>
            </div>
        </footer>

        <!-- JavaScript for Interactive Elements -->
        <script>
            // Mobile menu toggle
            document.addEventListener('DOMContentLoaded', function() {
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const mobileMenu = document.getElementById('mobile-menu');
                
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Smooth scrolling for anchor links
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        if (targetId === '#') return;
                        
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 80,
                                behavior: 'smooth'
                            });
                            
                            // Close mobile menu if open
                            mobileMenu.classList.add('hidden');
                        }
                    });
                });
            });
        </script>
    </body>
</html>