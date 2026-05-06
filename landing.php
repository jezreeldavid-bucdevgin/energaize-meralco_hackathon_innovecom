<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>energAIze - AI-Powered Energy Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <style>
        /* Base styles from feature.php */
        .neo-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        /* New styles for landing page */
        .gradient-text {
            background: linear-gradient(
                to right,
                #00F0FF,
                #6E00FF,
                #FF2D55,
                #00F0FF
            );
            background-size: 200% auto;
            animation: gradient 4s linear infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        @keyframes gradient {
            0% { background-position: 0% center; }
            100% { background-position: -200% center; }
        }

        .feature-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px) rotateX(5deg);
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 15px 35px rgba(0, 240, 255, 0.1),
                0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .cyber-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cyber-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .cyber-button:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 5px 15px rgba(0, 240, 255, 0.2),
                0 10px 30px rgba(110, 0, 255, 0.1);
        }

        .cyber-button:hover::before {
            left: 100%;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Glowing border effect */
        .glow-border {
            position: relative;
        }

        .glow-border::after {
            content: '';
            position: absolute;
            inset: -1px;
            background: linear-gradient(45deg, 
                rgba(0, 240, 255, 0.5), 
                rgba(110, 0, 255, 0.5), 
                rgba(255, 45, 85, 0.5)
            );
            filter: blur(5px);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .glow-border:hover::after {
            opacity: 1;
        }

        /* Floating animation */
        .float-animation {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Parallax container */
        .parallax-wrapper {
            position: relative;
            overflow: hidden;
        }

        .parallax-layer {
            position: absolute;
            width: 100%;
            height: 100%;
            transition: transform 0.1s ease-out;
            pointer-events: none;
        }

        .parallax-bg-1 {
            background: radial-gradient(circle at 50% 50%, rgba(0, 240, 255, 0.1) 0%, transparent 50%);
        }

        .parallax-bg-2 {
            background: radial-gradient(circle at 50% 50%, rgba(110, 0, 255, 0.1) 0%, transparent 50%);
        }

        .parallax-bg-3 {
            background: radial-gradient(circle at 50% 50%, rgba(255, 45, 85, 0.1) 0%, transparent 50%);
        }

        /* Enhanced section transitions */
        .section-transition {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section-transition.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Floating elements */
        .float-element {
            animation: float 6s ease-in-out infinite;
            transform-style: preserve-3d;
        }

        @keyframes float {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
            25% { transform: translate3d(10px, -10px, 20px) rotate(2deg); }
            50% { transform: translate3d(0, -20px, 40px) rotate(-1deg); }
            75% { transform: translate3d(-10px, -10px, 20px) rotate(1deg); }
        }

        /* Glowing text effect */
        .glow-text {
            text-shadow: 
                0 0 10px rgba(0, 240, 255, 0.5),
                0 0 20px rgba(0, 240, 255, 0.3),
                0 0 30px rgba(0, 240, 255, 0.1);
        }

        /* Enhanced card hover effect */
        .feature-card:hover .float-element {
            animation-play-state: paused;
        }

        /* Enhanced gradient animation */
        .hero-gradient {
            background: 
                linear-gradient(45deg, rgba(0, 240, 255, 0.1), transparent 70%),
                linear-gradient(-45deg, rgba(110, 0, 255, 0.1), transparent 70%),
                linear-gradient(180deg, rgba(255, 45, 85, 0.05), transparent 70%);
            background-size: 200% 200%, 200% 200%, 100% 100%;
            animation: gradientMove 10s ease infinite;
        }

        @keyframes gradientMove {
            0%, 100% { background-position: 0% 0%, 100% 0%, 0% 0%; }
            50% { background-position: 100% 100%, 0% 100%, 0% 100%; }
        }

        /* Enhanced card animations */
        .service-card {
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .service-card:hover .card-content {
            transform: translateZ(20px) rotateX(5deg);
        }

        .card-content {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }

        /* Enhanced navbar */
        .nav-link {
            position: relative;
            padding-bottom: 2px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00F0FF, #6E00FF);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-black min-h-screen font-poppins text-white">
    <!-- Particles Background -->
    <div id="particles-js"></div>

    <!-- Navbar -->
    <nav class="neo-glass fixed w-full z-50 px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="flex items-center space-x-3">
                <i class="fas fa-bolt text-3xl text-[#00F0FF] animate-pulse"></i>
                <img src="images/ener.png" alt="energAIze" class="h-8">
            </a>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-white">
                <i class="fas fa-bars text-2xl"></i>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="text-white hover:text-[#00F0FF] transition-colors">Home</a>
                <a href="#services" class="text-white hover:text-[#00F0FF] transition-colors">Services</a>
                <a href="#features" class="text-white hover:text-[#00F0FF] transition-colors">Features</a>
                <a href="#contact" class="text-white hover:text-[#00F0FF] transition-colors">Contact</a>
                <a href="about.php" class="text-white hover:text-[#00F0FF] transition-colors">About</a>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 neo-glass">
            <div class="px-4 py-2 space-y-3">
                <a href="#home" class="block text-white hover:text-[#00F0FF] py-2">Home</a>
                <a href="#services" class="block text-white hover:text-[#00F0FF] py-2">Services</a>
                <a href="#features" class="block text-white hover:text-[#00F0FF] py-2">Features</a>
                <a href="#contact" class="block text-white hover:text-[#00F0FF] py-2">Contact</a>
                <a href="about.php" class="block text-white hover:text-[#00F0FF] py-2">About</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center pt-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0A0A0A]/50 to-[#0A0A0A]"></div>
        <div class="content-wrapper container mx-auto px-4 py-16 text-center relative z-10">
            <div class="mb-8 float-animation">
                <i class="fas fa-bolt text-6xl text-[#00F0FF] animate-pulse"></i>
            </div>
            <h1 class="text-4xl md:text-7xl font-bold mb-6 gradient-text leading-tight">
                Transform Your Energy Data<br/>Into Actionable Insights
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-12 max-w-3xl mx-auto leading-relaxed">
                Harness the power of AI to analyze and optimize your building's energy consumption
            </p>
            <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
                <a href="feature.php" class="cyber-button neo-glass px-10 py-5 rounded-lg bg-gradient-to-r from-[#00F0FF] to-[#6E00FF] text-lg font-semibold">
                    Get Started
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#features" class="cyber-button neo-glass px-10 py-5 rounded-lg border border-[#00F0FF]/30 text-lg font-semibold">
                    Learn More
                    <i class="fas fa-chevron-down ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-32 relative">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0A0A0A] via-transparent to-[#0A0A0A]"></div>
        <div class="content-wrapper container mx-auto px-4 relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-16 text-center gradient-text">
                Our Services
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Enhanced Service Cards -->
                <div class="feature-card glow-border p-8 rounded-xl text-center">
                    <div class="text-[#00F0FF] text-5xl mb-6 float-animation">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Energy Analysis</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Comprehensive analysis of your building's energy consumption patterns using advanced AI algorithms
                    </p>
                </div>

                <!-- Service 2 -->
                <div class="feature-card glow-border p-8 rounded-xl text-center">
                    <div class="text-[#6E00FF] text-5xl mb-6 float-animation">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">AI-Powered Insights</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Advanced machine learning algorithms for predictive analysis
                    </p>
                </div>

                <!-- Service 3 -->
                <div class="feature-card glow-border p-8 rounded-xl text-center">
                    <div class="text-[#FF2D55] text-5xl mb-6 float-animation">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Custom Reports</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Detailed reports with actionable recommendations
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-transition py-20 bg-gradient-to-b from-transparent to-[#0A0A0A]/50">
        <div class="content-wrapper container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center gradient-text">
                Report Generation
            </h2>
            <div class="neo-glass p-8 rounded-xl max-w-4xl mx-auto">
                <!-- Using order classes to ensure correct layout on all screen sizes -->
                <div class="flex flex-col md:flex-row items-center gap-8">
                    <!-- Text content (left side) -->
                    <div class="flex-1 order-2 md:order-1">
                        <h3 class="text-2xl font-bold mb-4">Automated Energy Reports</h3>
                        <p class="text-gray-300 mb-6">
                            Upload your energy data and receive comprehensive analysis reports powered by our advanced AI system.
                        </p>
                        <a href="feature.php" class="cyber-button inline-block neo-glass px-6 py-3 rounded-lg bg-gradient-to-r from-[#00F0FF] to-[#6E00FF] hover:shadow-lg">
                            Generate Report
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    
                    <!-- Image (right side) -->
                    <div class="flex-1 order-1 md:order-2">
                        <img 
                            src="images/reports.png" 
                            alt="Report Example" 
                            class="rounded-lg shadow-2xl hover:scale-105 transition-transform duration-300"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section-transition py-20">
        <div class="content-wrapper container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center gradient-text">
                Get In Touch
            </h2>
            <div class="neo-glass p-8 rounded-xl max-w-2xl mx-auto">
                <form class="space-y-6">
                    <div>
                        <label class="block text-gray-300 mb-2">Name</label>
                        <input type="text" class="w-full px-4 py-3 rounded-lg neo-glass border border-[#00F0FF]/20 bg-transparent focus:border-[#00F0FF] transition-all">
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Email</label>
                        <input type="email" class="w-full px-4 py-3 rounded-lg neo-glass border border-[#00F0FF]/20 bg-transparent focus:border-[#00F0FF] transition-all">
                    </div>
                    <div>
                        <label class="block text-gray-300 mb-2">Message</label>
                        <textarea rows="4" class="w-full px-4 py-3 rounded-lg neo-glass border border-[#00F0FF]/20 bg-transparent focus:border-[#00F0FF] transition-all"></textarea>
                    </div>
                    <button type="submit" class="w-full cyber-button neo-glass px-6 py-3 rounded-lg bg-gradient-to-r from-[#00F0FF] to-[#6E00FF] hover:shadow-lg">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="neo-glass border-t border-[#00F0FF]/20 py-8">
        <div class="content-wrapper container mx-auto px-4 text-center">
            <p class="text-gray-300">
                © 2024 energAIze. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Particles.js configuration
        particlesJS('particles-js', {
            particles: {
                number: { value: 100 },
                color: { value: ['#00F0FF', '#6E00FF', '#FF2D55'] },
                shape: { type: 'circle' },
                opacity: {
                    value: 0.5,
                    random: true
                },
                size: {
                    value: 3,
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#A000C6',
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: true
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    }
                }
            }
        });

        // Parallax effect
        document.addEventListener('DOMContentLoaded', function() {
            const parallaxLayers = document.querySelectorAll('.parallax-layer');
            const sections = document.querySelectorAll('.section-transition');

            // Parallax scroll effect
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;

                parallaxLayers.forEach(layer => {
                    const speed = layer.getAttribute('data-speed');
                    const yPos = -(scrolled * speed);
                    layer.style.transform = `translate3d(0, ${yPos}px, 0)`;
                });

                // Section visibility check
                sections.forEach(section => {
                    const sectionTop = section.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (sectionTop < windowHeight * 0.75) {
                        section.classList.add('visible');
                    }
                });
            });

            // Mouse move parallax effect
            document.addEventListener('mousemove', (e) => {
                const mouseX = e.clientX;
                const mouseY = e.clientY;

                parallaxLayers.forEach(layer => {
                    const speed = layer.getAttribute('data-speed');
                    const x = (window.innerWidth - mouseX * speed) / 100;
                    const y = (window.innerHeight - mouseY * speed) / 100;
                    layer.style.transform = `translate3d(${x}px, ${y}px, 0)`;
                });
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Initialize sections as visible if they're already in view
        window.addEventListener('load', () => {
            document.querySelectorAll('.section-transition').forEach(section => {
                if (section.getBoundingClientRect().top < window.innerHeight * 0.75) {
                    section.classList.add('visible');
                }
            });
        });

        // Enhanced particle effect interaction with scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            if (window.pJSDom && window.pJSDom[0]) {
                const particles = window.pJSDom[0].pJS.particles;
                particles.move.speed = 2 + (scrolled * 0.001);
                particles.line_linked.opacity = 0.2 - (scrolled * 0.0001);
            }
        });
    </script>
</body>
</html>