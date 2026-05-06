<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - energAIze</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <style>
        .neo-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .team-card {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }

        .team-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transition: 0.5s;
        }

        .team-card:hover::before {
            left: 100%;
        }

        .team-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 15px 35px rgba(0, 240, 255, 0.1),
                0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .social-icon {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .social-icon::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00F0FF, #6E00FF);
            transition: width 0.3s ease;
        }

        .social-icon:hover::after {
            width: 100%;
        }

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

        .section-transition {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section-transition.visible {
            opacity: 1;
            transform: translateY(0);
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

        /* Responsive adjustments */
        @media (max-width: 1280px) {
            .team-card {
                max-width: 300px;
                margin: 0 auto;
            }
        }

        /* Card entrance animation */
        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-card {
            animation: cardEntrance 0.8s ease-out forwards;
            opacity: 0;
        }

        .team-card:nth-child(1) { animation-delay: 0.1s; }
        .team-card:nth-child(2) { animation-delay: 0.2s; }
        .team-card:nth-child(3) { animation-delay: 0.3s; }
        .team-card:nth-child(4) { animation-delay: 0.4s; }
        .team-card:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-black min-h-screen font-poppins text-white">
    <!-- Particles Background -->
    <div id="particles-js"></div>

    <!-- Navbar -->
    <nav class="neo-glass fixed w-full z-50 px-6 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="landing.php" class="flex items-center space-x-3">
                <i class="fas fa-bolt text-3xl text-[#00F0FF] animate-pulse"></i>
                <img src="images/ener.png" alt="energAIze" class="h-8">
            </a>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-white">
                <i class="fas fa-bars text-2xl"></i>
            </button>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-8">
                <a href="landing.php" class="text-white hover:text-[#00F0FF] transition-colors">Home</a>
                <a href="landing.php#services" class="text-white hover:text-[#00F0FF] transition-colors">Services</a>
                <a href="landing.php#features" class="text-white hover:text-[#00F0FF] transition-colors">Features</a>
                <a href="landing.php#contact" class="text-white hover:text-[#00F0FF] transition-colors">Contact</a>
                <a href="#" class="text-[#00F0FF]">About</a>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-0 right-0 neo-glass">
            <div class="px-4 py-2 space-y-3">
                <a href="landing.php" class="block text-white hover:text-[#00F0FF] py-2">Home</a>
                <a href="landing.php#services" class="block text-white hover:text-[#00F0FF] py-2">Services</a>
                <a href="landing.php#features" class="block text-white hover:text-[#00F0FF] py-2">Features</a>
                <a href="landing.php#contact" class="block text-white hover:text-[#00F0FF] py-2">Contact</a>
                <a href="#" class="block text-[#00F0FF] py-2">About</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-12">
        <!-- About Section -->
        <section class="section-transition py-16">
            <div class="content-wrapper container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center mb-16">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 gradient-text">Our Story</h1>
                    <p class="text-xl text-gray-300 leading-relaxed">
                        We are a team of passionate individuals dedicated to revolutionizing energy analysis through artificial intelligence. Our mission is to help businesses optimize their energy consumption and contribute to a sustainable future.
                    </p>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="section-transition py-16">
            <div class="content-wrapper container mx-auto px-4">
                <h2 class="text-3xl md:text-4xl font-bold mb-16 text-center gradient-text">Meet Our Team</h2>
                
                <!-- Team Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8 max-w-7xl mx-auto">
                    <!-- Team Member 1 -->
                    <div class="team-card relative p-6 rounded-xl text-center group">
                        <div class="relative w-32 h-32 mx-auto mb-6 group-hover:transform group-hover:scale-105 transition-all duration-300">
                            <!-- Animated gradient background -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00F0FF] via-[#6E00FF] to-[#FF2D55] rounded-full opacity-20 blur-lg animate-pulse"></div>
                            <!-- Profile image -->
                            <img 
                                src="images/saidelyn.jfif" 
                                alt="Saidelyn" 
                                class="relative w-full h-full object-cover rounded-full border-2 border-[#00F0FF]/30"
                                onerror="this.src='https://via.placeholder.com/128?text=S'"
                            >
                            <!-- Status indicator -->
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-[#00F0FF] rounded-full border-2 border-black"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-[#00F0FF] transition-colors">Saidelyn</h3>
                        <p class="text-[#00F0FF] mb-4 opacity-75 group-hover:opacity-100">Project Manager</p>
                        <p class="text-gray-400 mb-6 text-sm">Visionary leader with expertise in AI and energy management systems</p>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="team-card relative p-6 rounded-xl text-center group">
                        <div class="relative w-32 h-32 mx-auto mb-6 group-hover:transform group-hover:scale-105 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00F0FF] via-[#6E00FF] to-[#FF2D55] rounded-full opacity-20 blur-lg animate-pulse"></div>
                            <img 
                                src="images/sean.jfif" 
                                alt="Sean" 
                                class="relative w-full h-full object-cover rounded-full border-2 border-[#00F0FF]/30"
                                onerror="this.src='https://via.placeholder.com/128?text=S'"
                            >
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-[#00F0FF] rounded-full border-2 border-black"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-[#00F0FF] transition-colors">Sean</h3>
                        <p class="text-[#00F0FF] mb-4 opacity-75 group-hover:opacity-100">Front End Developer</p>
                        <p class="text-gray-400 mb-6 text-sm">Creative designer focused on intuitive user experiences</p>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="team-card relative p-6 rounded-xl text-center group">
                        <div class="relative w-32 h-32 mx-auto mb-6 group-hover:transform group-hover:scale-105 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00F0FF] via-[#6E00FF] to-[#FF2D55] rounded-full opacity-20 blur-lg animate-pulse"></div>
                            <img 
                                src="images/jezreel.jfif" 
                                alt="Jezreel" 
                                class="relative w-full h-full object-cover rounded-full border-2 border-[#00F0FF]/30"
                                onerror="this.src='https://via.placeholder.com/128?text=J'"
                            >
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-[#00F0FF] rounded-full border-2 border-black"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-[#00F0FF] transition-colors">Jezreel</h3>
                        <p class="text-[#00F0FF] mb-4 opacity-75 group-hover:opacity-100">Team Leader</p>
                        <p class="text-gray-400 mb-6 text-sm">Full-stack developer specializing in energy analytics platforms</p>
                    </div>

                    <!-- Team Member 4 -->
                    <div class="team-card relative p-6 rounded-xl text-center group">
                        <div class="relative w-32 h-32 mx-auto mb-6 group-hover:transform group-hover:scale-105 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00F0FF] via-[#6E00FF] to-[#FF2D55] rounded-full opacity-20 blur-lg animate-pulse"></div>
                            <img 
                                src="images/steven.jfif" 
                                alt="Steven" 
                                class="relative w-full h-full object-cover rounded-full border-2 border-[#00F0FF]/30"
                                onerror="this.src='https://via.placeholder.com/128?text=S'"
                            >
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-[#00F0FF] rounded-full border-2 border-black"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-[#00F0FF] transition-colors">Steven</h3>
                        <p class="text-[#00F0FF] mb-4 opacity-75 group-hover:opacity-100">NLP Engineer & Back End Developer</p>
                        <p class="text-gray-400 mb-6 text-sm">AI/ML specialist with focus on energy consumption patterns</p>
                    </div>

                    <!-- Team Member 5 -->
                    <div class="team-card relative p-6 rounded-xl text-center group">
                        <div class="relative w-32 h-32 mx-auto mb-6 group-hover:transform group-hover:scale-105 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#00F0FF] via-[#6E00FF] to-[#FF2D55] rounded-full opacity-20 blur-lg animate-pulse"></div>
                            <img 
                                src="images/hanna.jfif" 
                                alt="Hanna" 
                                class="relative w-full h-full object-cover rounded-full border-2 border-[#00F0FF]/30"
                                onerror="this.src='https://via.placeholder.com/128?text=H'"
                            >
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-[#00F0FF] rounded-full border-2 border-black"></div>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-[#00F0FF] transition-colors">Hanna</h3>
                        <p class="text-[#00F0FF] mb-4 opacity-75 group-hover:opacity-100">Spokesperson & Documentation</p>
                        <p class="text-gray-400 mb-6 text-sm">Expert in pitching and documentation</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="neo-glass border-t border-[#00F0FF]/20 py-8">
        <div class="content-wrapper container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center md:text-left">
                    <h3 class="text-xl font-bold mb-4">energAIze</h3>
                    <p class="text-gray-400">Transforming energy analysis with AI</p>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <div class="space-y-2">
                        <a href="landing.php" class="block text-gray-400 hover:text-[#00F0FF]">Home</a>
                        <a href="landing.php#services" class="block text-gray-400 hover:text-[#00F0FF]">Services</a>
                        <a href="landing.php#contact" class="block text-gray-400 hover:text-[#00F0FF]">Contact</a>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <h3 class="text-xl font-bold mb-4">Connect With Us</h3>

                </div>
            </div>
            <div class="mt-8 text-center text-gray-400">
                <p>&copy; 2024 energAIze. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.section-transition');

            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                observer.observe(section);
            });
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
    </script>
</body>
</html>
