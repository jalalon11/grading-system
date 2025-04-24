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
                background-clip: text;
                -webkit-text-fill-color: transparent;
                font-weight: 700;
            }

            /* Benefits Section styling */
            .cta-section {
                padding: 80px 0;
                text-align: center;
                background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
            }

            .cta-section .text-indigo-500 {
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
                        <a href="#pricing" class="text-gray-700 hover:text-gray-900">Pricing</a>
                        <a href="#benefits" class="text-gray-700 hover:text-gray-900">Benefits</a>
                        <a href="#developer" class="text-gray-700 hover:text-gray-900">Developer</a>
                        @auth
                            <a href="{{ url('login') }}" class="btn-primary text-white ml-4">
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
                <a href="#pricing" class="block py-2 text-gray-700">Pricing</a>
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

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 bg-[#F4F6F6]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <span class="inline-block px-4 py-2 rounded-full bg-[#1C2833] text-[#F4F6F6] text-sm font-semibold mb-4">Pricing Plans</span>
                    <h2 class="text-4xl font-bold text-[#1C2833] mb-4">Simple, Transparent Pricing</h2>
                    <p class="text-xl text-[#2E4053] max-w-2xl mx-auto">Choose the perfect plan based on your school's grade levels. Start with a free trial, no credit card required.</p>
                </div>

                <!-- Trial Banner -->
                <div class="bg-gradient-to-r from-[#1C2833] to-[#2E4053] rounded-2xl p-8 mb-12 max-w-4xl mx-auto shadow-lg">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="flex items-center mb-6 md:mb-0">
                            <div class="bg-[#F4F6F6] bg-opacity-20 rounded-full p-3 mr-4">
                                <i class="fas fa-gift text-[#F4F6F6] text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-[#F4F6F6] mb-2">3-Months Free Trial</h3>
                                <p class="text-[#AAB7B8]">Experience all premium features with no commitment</p>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="bg-[#F4F6F6] text-[#1C2833] px-6 py-3 rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
                            Start Free Trial
                        </a>
                    </div>
                </div>

                <!-- Billing Toggle -->
                <div class="flex justify-center mb-12">
                    <div class="bg-[#F4F6F6] p-1 rounded-xl shadow-sm inline-flex">
                        <button type="button" class="px-6 py-2 text-sm font-medium rounded-lg bg-[#1C2833] text-[#F4F6F6] transition-colors">
                            Monthly Billing
                        </button>
                        <button type="button" class="px-6 py-2 text-sm font-medium rounded-lg text-[#2E4053] transition-colors">
                            Annual Billing
                            <span class="ml-2 px-2 py-1 text-xs bg-[#2E4053] rounded-full text-[#F4F6F6]">Save 20%</span>
                        </button>
                    </div>
                </div>

                <!-- Pricing Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <!-- Elementary Plan -->
                    <div class="bg-[#F4F6F6] rounded-2xl shadow-lg border border-[#AAB7B8] overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-[#1C2833] mb-2">Elementary</h3>
                            <p class="text-[#2E4053] mb-6">Kindergarten to Grade 6</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-[#1C2833]">₱2,300</span>
                                <span class="text-[#2E4053]">/month</span>
                                <div class="text-sm text-[#2E4053] mt-1">or ₱22,080/year</div>
                            </div>
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Unlimited students</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Advanced analytics</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Custom reports</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Priority support</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Core Functionality</span>
                                </li>
                            </ul>
                            <div class="mt-6 p-4 bg-[#1C2833] bg-opacity-5 rounded-lg">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <i class="fas fa-book text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-open text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-reader text-[#1C2833] text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- High School Plan -->
                    <div class="bg-[#F4F6F6] rounded-2xl shadow-xl border-2 border-[#1C2833] overflow-hidden transform hover:scale-105 transition-transform duration-300 relative">
                        <div class="absolute top-0 right-0 bg-[#1C2833] text-[#F4F6F6] px-4 py-1 rounded-bl-lg text-sm font-semibold">
                            Most Popular
                        </div>
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-[#1C2833] mb-2">High School</h3>
                            <p class="text-[#2E4053] mb-6">Grade 7 to Grade 12</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-[#1C2833]">₱2,500</span>
                                <span class="text-[#2E4053]">/month</span>
                                <div class="text-sm text-[#2E4053] mt-1">or ₱24,000/year</div>
                            </div>
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Unlimited students</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Advanced analytics</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Custom reports</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Priority support</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Core Functionality</span>
                                </li>
                            </ul>
                            <div class="mt-6 p-4 bg-[#1C2833] bg-opacity-5 rounded-lg">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <i class="fas fa-book text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-open text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-reader text-[#1C2833] text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complete System Plan -->
                    <div class="bg-[#F4F6F6] rounded-2xl shadow-lg border border-[#AAB7B8] overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-[#1C2833] mb-2">Complete System</h3>
                            <p class="text-[#2E4053] mb-6">Kindergarten to Grade 12</p>
                            <div class="mb-6">
                                <span class="text-4xl font-bold text-[#1C2833]">₱4,700</span>
                                <span class="text-[#2E4053]">/month</span>
                                <div class="text-sm text-[#2E4053] mt-1">or ₱45,120/year</div>
                            </div>
                            <ul class="space-y-4 mb-8">
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Unlimited students</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Advanced analytics</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Custom reports</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Priority support</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Core Functionality</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-check text-[#1C2833] mr-3"></i>
                                    <span class="text-[#2E4053]">Free 3 months</span>
                                </li>
                            </ul>
                            <div class="mt-6 p-4 bg-[#1C2833] bg-opacity-5 rounded-lg">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        <i class="fas fa-book text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-open text-[#1C2833] text-2xl"></i>
                                        <i class="fas fa-book-reader text-[#1C2833] text-2xl"></i>
                                    </div>
                                </div>
                            </div>
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
                                        Vincent Jalalon is a dedicated full-stack developer with over 2 years of experience in building technology solutions. As the founder of VSMART TUNE UP, Vincent brings a unique perspective to developing tools that enhance the grading system experience, driven by a passion for innovation and efficiency in education technology.
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
                                                <li>Database: MySQL</li>
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
                                                <p class="text-gray-600">Public API for third-party integrations</p>
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
        <footer class="footer bg-gradient-to-b from-[#1C2833] to-[#17202A]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                    <!-- About Column -->
                    <div class="col-span-1 lg:col-span-1">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-[#2E4053] to-[#5D6D7E] rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white">Grading System</h3>
                        </div>
                        <p class="text-gray-400 mb-4">
                            Transforming how educational institutions manage and analyze student performance.
                        </p>
                        <div class="flex space-x-3 mt-5">
                            <a href="https://www.facebook.com/vivinz11" target="_blank" class="w-9 h-9 rounded-full bg-[#2E4053] hover:bg-[#5D6D7E] flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-facebook-f text-white"></i>
                            </a>
                            <a href="https://github.com/jalalon11" target="_blank" class="w-9 h-9 rounded-full bg-[#2E4053] hover:bg-[#5D6D7E] flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-github text-white"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/vincent-jhanrey-jalalon-184299347/" target="_blank" class="w-9 h-9 rounded-full bg-[#2E4053] hover:bg-[#5D6D7E] flex items-center justify-center transition-colors duration-300">
                                <i class="fab fa-linkedin-in text-white"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links Column -->
                    <div class="col-span-1">
                        <h4 class="text-lg font-medium text-white mb-5 border-b border-gray-700 pb-2">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Home</a></li>
                            <li><a href="#features" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Features</a></li>
                            <li><a href="#pricing" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Pricing</a></li>
                            <li><a href="#benefits" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Benefits</a></li>
                            <li><a href="#developer" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Developer</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors flex items-center"><i class="fas fa-chevron-right text-xs mr-2"></i> Login</a></li>
                        </ul>
                    </div>

                    <!-- Development Status Column -->
                    <div class="col-span-1">
                        <h4 class="text-lg font-medium text-white mb-5 border-b border-gray-700 pb-2">Development Status</h4>
                        <div class="flex items-center mb-3 bg-[#2E4053] p-3 rounded-lg">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full mr-3 animate-pulse"></div>
                            <p class="text-gray-300">System in development</p>
                        </div>
                        <div class="flex items-center mb-3 bg-[#2E4053] p-3 rounded-lg">
                            <i class="fas fa-code-branch text-gray-300 mr-3"></i>
                            <p class="text-gray-300">Version: 1.0.0 Beta</p>
                        </div>
                        <div class="mt-4">
                            <p class="text-gray-400 mb-2">Have suggestions?</p>
                            <a href="mailto:vinz0799@gmail.com" class="text-gray-400 hover:text-white transition-colors flex items-center bg-[#2E4053] p-3 rounded-lg">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send feedback
                            </a>
                        </div>
                    </div>

                    <!-- Newsletter Column -->
                    <div class="col-span-1 lg:col-span-1">
                        <h4 class="text-lg font-medium text-white mb-5 border-b border-gray-700 pb-2">Newsletter</h4>
                        <p class="text-gray-400 mb-4">Subscribe to receive updates about new features and releases.</p>
                        <form class="space-y-3">
                            <div>
                                <input type="email" placeholder="Your email address" class="w-full px-4 py-2 rounded-lg bg-[#2E4053] border border-[#5D6D7E] text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#5D6D7E] focus:border-transparent">
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-[#2E4053] to-[#5D6D7E] hover:from-[#5D6D7E] hover:to-[#2E4053] text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-envelope mr-2"></i> Subscribe
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="border-t border-gray-700 pt-6 flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
                    <p>System in development. &copy; {{ date('Y') }} Developed by VSMART TUNE UP.</p>
                    <!-- <div class="mt-3 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors mx-2">Privacy Policy</a>
                        <span class="text-gray-600">|</span>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors mx-2">Terms of Service</a>
                    </div> -->
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

                // Pricing tabs functionality
                const elementaryTab = document.getElementById('elementary-tab');
                const highschoolTab = document.getElementById('highschool-tab');
                const completeTab = document.getElementById('complete-tab');

                // Function to handle tab switching
                function switchTab(activeTab, inactiveTabs) {
                    // Activate the selected tab
                    activeTab.classList.remove('bg-white', 'text-gray-700');
                    activeTab.classList.add('bg-blue-600', 'text-white');

                    // Deactivate other tabs
                    inactiveTabs.forEach(tab => {
                        tab.classList.remove('bg-blue-600', 'text-white');
                        tab.classList.add('bg-white', 'text-gray-700');
                    });
                }

                // Set up event listeners for tabs
                elementaryTab.addEventListener('click', function() {
                    switchTab(elementaryTab, [highschoolTab, completeTab]);
                });

                highschoolTab.addEventListener('click', function() {
                    switchTab(highschoolTab, [elementaryTab, completeTab]);
                });

                completeTab.addEventListener('click', function() {
                    switchTab(completeTab, [elementaryTab, highschoolTab]);
                });

            });

            document.addEventListener('alpine:init', () => {
                Alpine.data('pricing', () => ({
                    billing: 'monthly',
                    init() {
                        // Initialize the billing state
                        this.billing = 'monthly';
                    }
                }));
            });
        </script>
    </body>
</html>
