<?php
// Create uploads directory if it doesn't exist
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Add this PHP code at the top of your file
$templatePath = 'uploads/Template.xlsx';
$templateExists = file_exists($templatePath);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Add Font Awesome for better icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'neon-blue': '#00F0FF',
                        'deep-purple': '#6E00FF',
                        'cyber-pink': '#FF2D55',
                        'electric-green': '#39FF14',
                        'space-black': '#0A0A0A',
                        'cyber-gray': '#1E1E1E',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float-slow': 'float 3s ease-in-out infinite',
                        'matrix-fade': 'matrixFade 15s linear infinite',
                        'fade-in': 'fadeIn 0.5s ease-in',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'bounce-slow': 'bounce 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .neo-glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .cyber-border {
            position: relative;
            border: 1px solid rgba(0, 240, 255, 0.2);
        }
        .cyber-border::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border: 2px solid transparent;
            border-radius: inherit;
            background: linear-gradient(45deg, #00F0FF, #6E00FF, #FF2D55) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            animation: borderRotate 4s linear infinite;
        }
        @keyframes matrixFade {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }
        .cyber-gradient {
            background: linear-gradient(
                45deg,
                rgba(0, 240, 255, 0.1),
                rgba(110, 0, 255, 0.1),
                rgba(255, 45, 85, 0.1)
            );
            background-size: 200% 200%;
            animation: gradientMove 8s ease infinite;
        }
        .scale-95 {
            transform: scale(0.95);
        }
        .scale-100 {
            transform: scale(1);
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: transparent;
            z-index: 0;
        }
        /* Ensure content stays above particles */
        .relative-z {
            position: relative;
            z-index: 1;
        }
    </style>
    <!-- Add Particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>
<body class="bg-space-black min-h-screen flex flex-col font-poppins text-gray-100">
    <!-- Add this at the top of your body tag -->
    <div id="errorModal" class="fixed inset-0 z-50 hidden">
        <!-- Modal backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
        
        <!-- Modal content -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform transition-all">
                <div class="p-6">
                    <!-- Modal header -->
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Required Fields Missing</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="errorMessage"></p>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="mt-6">
                        <button type="button" 
                                onclick="closeModal()"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Particle Animation Container -->
    <div id="particles-js" class="fixed inset-0 z-0 pointer-events-none"></div>

    <!-- Header with glass effect -->
    <header class="neo-glass px-6 py-4 shadow-lg relative relative-z border-b border-neon-blue/20">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <nav class="container mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 relative z-10">
            <a href="index.php" class="flex items-center space-x-3 hover:scale-105 transition-transform duration-300">
                <i class="fas fa-bolt text-white text-3xl animate-float"></i>
                <img src="images/ener.png" alt="energAIze" class="h-10 w-auto">
            </a>
            <div class="glass-effect px-6 py-2 rounded-full">
                <div class="space-x-6">
                    Test
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto flex-grow px-4 py-12 relative-z">
        <div class="max-w-4xl mx-auto neo-glass rounded-2xl p-8 cyber-border">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple/10 to-red/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-orange/10 to-blue/10 rounded-full -ml-16 -mb-16"></div>
            
            <!-- Header Section with Enhanced Design -->
            <div class="relative z-10 border-b border-gray-200/20 pb-8 mb-8">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-gradient-to-br from-blue/10 to-purple/10 rounded-xl">
                        <i class="fas fa-microchip text-4xl bg-gradient-to-r from-neon-blue via-deep-purple to-cyber-pink bg-clip-text text-transparent"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white">
                            EnergAIze Data Input
                        </h2>
                        <p class="text-white mt-1">Complete the form below to generate your energy analysis report</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="relative z-10">
                <form class="space-y-8 cyber-gradient p-6 rounded-xl" 
                      action="insert_company.php" 
                      method="post" 
                      enctype="multipart/form-data"
                      onsubmit="return handleSubmit(event)">
                    
                    <!-- Add hidden input for company ID -->
                    <input type="hidden" name="company_id" value="<?php echo $_SESSION['company_id']; ?>">
                    
                    <!-- Form Grid with Enhanced Styling -->
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Company Name with Enhanced Input -->
                        <div class="space-y-2">
                            <label class="flex items-center font-semibold mb-2">
                                <div class="p-2 bg-cyber-gray/50 rounded-lg mr-2">
                                    <i class="fas fa-building text-neon-blue animate-pulse-glow"></i>
                                </div>
                                Company Name <span class="text-cyber-pink ml-1">*</span>
                            </label>
                            <input type="text" 
                                   name="company_name"
                                   id="company_name"
                                   class="w-full px-4 py-3 bg-cyber-gray/30 neo-glass border border-neon-blue/20 rounded-lg focus:ring-2 focus:ring-neon-blue focus:border-transparent transition-all duration-300 text-white">
                        </div>

                        <!-- Add Gross Area field -->
                        <div class="space-y-2">
                            <label class="flex items-center font-semibold mb-2">
                                <div class="p-2 bg-cyber-gray/50 rounded-lg mr-2">
                                    <i class="fas fa-building-circle-check text-neon-blue animate-pulse-glow"></i>
                                </div>
                                Building Gross Area (m²) <span class="text-cyber-pink ml-1">*</span>
                            </label>
                            <input type="number" 
                                   name="gross_area"
                                   id="gross_area"
                                   min="0"
                                   step="0.01"
                                   class="w-full px-4 py-3 bg-cyber-gray/30 neo-glass border border-neon-blue/20 rounded-lg focus:ring-2 focus:ring-neon-blue focus:border-transparent transition-all duration-300 text-white"
                                   placeholder="Enter gross area in square meters">
                        </div>

                        <!-- Company Logo with Enhanced Upload Area -->
                        <div class="space-y-2 transform transition-all duration-300 hover:scale-105">
                            <label class="flex items-center text-white font-semibold mb-2">
                                <div class="p-2 bg-purple-50/10 rounded-lg mr-2">
                                    <i class="fas fa-image text-purple-400"></i>
                                </div>
                                Company Logo <span class="text-gray-400 ml-1">(Optional)</span>
                            </label>
                            <div class="relative">
                                <input type="file" 
                                       name="company_logo"
                                       id="company_logo"
                                       accept="image/*"
                                       class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:ring-2 focus:ring-purple focus:border-transparent hover:bg-gray-50 transition-all duration-300 cursor-pointer shadow-sm"
                                       onchange="previewLogo(this)">
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Supported formats: PNG, JPG, JPEG (max 2MB)
                                </p>
                                <!-- Enhanced Logo Preview -->
                                <div id="logoPreview" class="hidden mt-4 p-2 bg-gray-50 rounded-lg">
                                    <img src="" alt="Logo Preview" class="max-h-20 rounded-lg shadow-md mx-auto">
                                </div>
                            </div>
                        </div>

                        <!-- Template Download Section with Enhanced Design -->
                        <div class="space-y-2 md:col-span-2 backdrop-blur-md bg-[#FEDEBE]/10 p-6 rounded-xl border border-[#FEDEBE]/20 shadow-lg" style="background: rgba(254, 222, 190, 0.05);">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-[#FEDEBE]/10 backdrop-blur-sm rounded-lg mr-3 animate-pulse">
                                        <i class="fas fa-exclamation-triangle text-[#FFAF42] text-lg"></i>
                                    </div>
                                    <span class="text-[#FFAF42] font-semibold">Important Note:</span>
                                </div>
                                <?php if ($templateExists): ?>
                                    <button onclick="window.location.href='<?php echo $templatePath; ?>'" 
                                            class="flex items-center gap-2 px-6 py-2.5 gradient-animate bg-gradient-to-r from-red via-orange to-purple text-white rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 group backdrop-blur-sm">
                                        <i class="fas fa-download group-hover:-translate-y-1 transition-transform duration-300"></i>
                                        Download Template
                                    </button>
                                <?php else: ?>
                                    <span class="text-[#FFAF42]">Template file not found</span>
                                <?php endif; ?>
                            </div>
                            <div class="mt-2 ml-12 p-3 backdrop-blur-sm bg-[#FEDEBE]/5 border-l-4 border-[#FFAF42] rounded">
                                <p class="text-[#FFAF42] text-sm font-medium">
                                    <span class="block mb-1 font-bold underline">Requirement:</span>
                                    Please download and use this template for your data submission. 
                                    <span class="font-bold">The Excel file must follow the exact format provided in the template.</span>
                                    <span class="block mt-1 text-xs text-[#FFAF42]">
                                        * Incorrect formats may result in processing errors
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Excel File Upload with Enhanced Design -->
                        <div class="space-y-2 md:col-span-2 transform transition-all duration-300 hover:scale-105">
                            <label class="flex items-center text-white font-semibold mb-2">
                                <div class="p-2 bg-green-50/10 rounded-lg mr-2">
                                    <i class="fas fa-file-excel text-green-400"></i>
                                </div>
                                Upload Excel File <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input type="file" 
                                       name="excel_file"
                                       id="excel_file"
                                       accept=".xlsx,.xls" 
                                       required
                                       class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:ring-2 focus:ring-purple focus:border-transparent hover:bg-gray-50 transition-all duration-300 cursor-pointer shadow-sm"
                                       onchange="updateFileName(this)">
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Supported formats: .xlsx, .xls
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submit Button -->
                    <button type="submit" 
                            class="w-full neo-glass bg-gradient-to-r from-neon-blue via-deep-purple to-cyber-pink text-white font-bold py-4 px-8 rounded-xl hover:shadow-[0_0_20px_rgba(0,240,255,0.5)] transition-all duration-300">
                        <span class="flex items-center justify-center gap-3">
                            <i class="fas fa-upload animate-float-slow"></i>
                            Generate Reports
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Enhanced Footer -->
    <footer class="neo-glass border-t border-neon-blue/20 px-8 py-6 mt-12 relative-z">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center text-white">
            <p class="flex items-center space-x-2">
                <i class="fas fa-bolt animate-pulse"></i>
                <span>&copy; 2024 energAIze - Powered by NLP</span>
            </p>
        </div>
    </footer>
    <script>
        function validateForm(event) {
            event.preventDefault();
            
            const companyName = document.getElementById('company_name').value.trim();
            const grossArea = document.getElementById('gross_area').value.trim();
            const excelFile = document.getElementById('excel_file').files[0];
            let errors = [];

            // Validate Company Name
            if (!companyName) {
                errors.push("Company Name is required");
            }

            // Validate Gross Area
            if (!grossArea) {
                errors.push("Building Gross Area is required");
            } else if (grossArea <= 0) {
                errors.push("Building Gross Area must be greater than 0");
            }

            // Validate Excel File
            if (!excelFile) {
                errors.push("Excel File is required");
            } else {
                const fileExt = excelFile.name.split('.').pop().toLowerCase();
                if (!['xlsx', 'xls'].includes(fileExt)) {
                    errors.push("Please upload a valid Excel file (.xlsx or .xls)");
                }
            }

            // Check Logo if uploaded
            const logoFile = document.getElementById('company_logo').files[0];
            if (logoFile) {
                const logoSize = logoFile.size / 1024 / 1024; // Convert to MB
                if (logoSize > 2) {
                    errors.push("Logo file size must be less than 2MB");
                }
                
                const logoExt = logoFile.name.split('.').pop().toLowerCase();
                if (!['jpg', 'jpeg', 'png'].includes(logoExt)) {
                    errors.push("Logo must be in JPG, JPEG, or PNG format");
                }
            }

            if (errors.length > 0) {
                showModal(errors.join("<br><br>"));
                return false;
            }

            // If validation passes, submit the form
            event.target.submit();
            return true;
        }

        function showModal(message) {
            const modal = document.getElementById('errorModal');
            const messageElement = document.getElementById('errorMessage');
            
            // Set the error message
            messageElement.innerHTML = message;
            
            // Show the modal
            modal.classList.remove('hidden');
            
            // Add animation classes
            requestAnimationFrame(() => {
                modal.querySelector('.bg-white').classList.add('scale-100');
                modal.querySelector('.bg-white').classList.remove('scale-95');
            });
        }

        function closeModal() {
            const modal = document.getElementById('errorModal');
            
            // Add closing animation
            modal.querySelector('.bg-white').classList.add('scale-95');
            modal.querySelector('.bg-white').classList.remove('scale-100');
            
            // Hide the modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('errorModal');
            if (!modal.classList.contains('hidden')) {
                const modalContent = modal.querySelector('.bg-white');
                if (!modalContent.contains(event.target) && !event.target.closest('button')) {
                    closeModal();
                }
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        function previewLogo(input) {
            const preview = document.getElementById('logoPreview');
            const previewImg = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                // Check file size
                if (input.files[0].size > 2 * 1024 * 1024) {
                    showModal("Logo file size must be less than 2MB");
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

        function updateFileName(input) {
            const fileName = input.files[0]?.name;
            if (fileName) {
                input.nextElementSibling.textContent = `Selected file: ${fileName}`;
            } else {
                input.nextElementSibling.textContent = 'Supported formats: .xlsx, .xls';
            }
        }

        function handleSubmit(event) {
            event.preventDefault();
            
            if (!validateForm(event)) {
                return false;
            }

            const form = event.target;
            const formData = new FormData(form);

            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitButton.disabled = true;

            // First, send to insert_company.php
            fetch('insert_company.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // After successful insert, send Excel file to process_excel.php
                    const excelFormData = new FormData();
                    excelFormData.append('excel_file', formData.get('excel_file'));
                    excelFormData.append('company_id', data.company_id); // Pass the company_id from insert_company.php

                    return fetch('process_excel.php', {
                        method: 'POST',
                        body: excelFormData
                    });
                } else {
                    throw new Error(data.error || 'Error inserting company data');
                }
            })
            .then(response => response.json())
            .then(analysisData => {
                if (analysisData.success) {
                    showResults({
                        ...analysisData,
                        company: formData.get('company_name'),
                        grossArea: formData.get('gross_area')
                    });
                } else {
                    throw new Error(analysisData.error || 'Error processing Excel file');
                }
            })
            .catch(error => {
                showModal(error.message || 'An error occurred while processing your request.');
                console.error('Error:', error);
            })
            .finally(() => {
                // Restore button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
            });

            return false;
        }

        function showResults(data) {
            // Create a results section in the main content area
            const resultsHTML = `
                <div class="mt-8 neo-glass p-6 rounded-xl animate-fade-in">
                    <h3 class="text-2xl font-bold mb-4 gradient-text">Analysis Results</h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-cyber-gray/30 rounded-lg">
                            <p class="text-white">${data.analysis}</p>
                        </div>
                        <div class="flex justify-between text-sm text-gray-400">
                            <span>Company: ${data.company}</span>
                            <span>Gross Area: ${data.grossArea} m²</span>
                        </div>
                    </div>
                </div>
            `;

            // Add results to the page
            const formContainer = document.querySelector('form').parentElement;
            formContainer.insertAdjacentHTML('afterend', resultsHTML);

            // Scroll to results
            document.querySelector('.animate-fade-in').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
    <!-- Particle Animation Configuration -->
    <script>
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 100,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ['#00F0FF', '#6E00FF', '#FF2D55', '#39FF14']
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 2,
                        size_min: 0.1,
                        sync: false
                    }
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
                    random: true,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                    attract: {
                        enable: true,
                        rotateX: 600,
                        rotateY: 1200
                    }
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
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 0.5
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>