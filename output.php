<?php
// Start session at the very beginning of the file, before any output
session_start();

include 'db_connect.php';
require_once 'vendor/autoload.php';

// Get the latest company profile and execute Python analysis
$query = "SELECT * FROM company_profiles ORDER BY company_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$company = mysqli_fetch_assoc($result);

// Debug logging
error_log("Company data: " . print_r($company, true));

// Add null check before accessing array
if ($company && !empty($company['company_excel'])) {
    $excelPath = $company['company_excel'];
    error_log("Excel path: " . $excelPath);

    // Verify file exists
    if (!file_exists($excelPath)) {
        error_log("Excel file not found at path: " . $excelPath);
        $executiveSummary = "Error: Excel file not found.";
    } else {
        // Execute Python script with the Excel file path
        try {
            // Get absolute path to model.py
            $modelPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'model.py';
            
            // Execute Python command
            $command = sprintf(
                'python "%s" "%s" 2>&1',
                escapeshellarg($modelPath),
                escapeshellarg($excelPath)
            );
            
            error_log("Executing command: " . $command);
            
            // Execute the command and capture output
            $pythonOutput = shell_exec($command);
            
            error_log("Raw Python output: " . $pythonOutput);
            
            // Parse the JSON response
            if ($pythonOutput) {
                // Look for the JSON response after the Raw Analysis Output marker
                if (strpos($pythonOutput, "=== JSON Response ===") !== false) {
                    $parts = explode("=== JSON Response ===", $pythonOutput);
                    $jsonPart = trim(end($parts));
                    $jsonResponse = json_decode($jsonPart, true);
                } else {
                    $jsonResponse = json_decode($pythonOutput, true);
                }
                
                if ($jsonResponse && isset($jsonResponse['success'])) {
                    if ($jsonResponse['success']) {
                        $executiveSummary = $jsonResponse['summary'];
                    } else {
                        error_log("Python script error: " . ($jsonResponse['error'] ?? 'Unknown error'));
                        $executiveSummary = "Error generating analysis: " . ($jsonResponse['error'] ?? 'Please try again.');
                    }
                } else {
                    error_log("Invalid JSON structure from Python script");
                    $executiveSummary = "Error: Invalid response format from analysis engine.";
                }
            } else {
                error_log("No output from Python script");
                $executiveSummary = "Error: No response from analysis engine.";
            }
        } catch (Exception $e) {
            error_log("Exception running Python script: " . $e->getMessage());
            $executiveSummary = "Error: " . $e->getMessage();
        }
    }
} else {
    $executiveSummary = "No company profile or Excel file found.";
    error_log("Missing company profile or Excel file path");
}

// Store the executive summary in session
$_SESSION['executive_summary'] = $executiveSummary;

// For debugging, you can also store the raw Python output
$_SESSION['debug_python_output'] = $pythonOutput ?? 'No Python output';

// Add this near the top of your PHP section, after getting the company profile
$excelData = [];
$buildingData = [];
$typeData = [];

if ($company && !empty($company['company_excel']) && file_exists($company['company_excel'])) {
    // Read Excel file
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($company['company_excel']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Skip header row
    array_shift($rows);
    
    // Process data for both stacked area charts
    $buildingYearlyData = [];
    $typeYearlyData = [];
    $buildings = [];
    $types = [];
    
    foreach ($rows as $row) {
        $building = $row[0];
        $type = $row[1];
        $year = $row[2];
        $demand = floatval($row[3]);
        
        // Building data
        if (!isset($buildingYearlyData[$year])) {
            $buildingYearlyData[$year] = [];
        }
        if (!isset($buildingYearlyData[$year][$building])) {
            $buildingYearlyData[$year][$building] = 0;
        }
        $buildingYearlyData[$year][$building] += $demand;
        
        // Type data
        if (!isset($typeYearlyData[$year])) {
            $typeYearlyData[$year] = [];
        }
        if (!isset($typeYearlyData[$year][$type])) {
            $typeYearlyData[$year][$type] = 0;
        }
        $typeYearlyData[$year][$type] += $demand;
        
        // Keep track of unique buildings and types
        if (!in_array($building, $buildings)) {
            $buildings[] = $building;
        }
        if (!in_array($type, $types)) {
            $types[] = $type;
        }
    }
    
    // Sort years
    ksort($buildingYearlyData);
    ksort($typeYearlyData);
    
    // Prepare data for charts
    $years = array_keys($buildingYearlyData);
    
    // Colors for different categories
    $colors = [
        ['rgba(0, 240, 255, 0.5)', 'rgba(0, 240, 255, 1)'],
        ['rgba(110, 0, 255, 0.5)', 'rgba(110, 0, 255, 1)'],
        ['rgba(255, 45, 85, 0.5)', 'rgba(255, 45, 85, 1)'],
        ['rgba(57, 255, 20, 0.5)', 'rgba(57, 255, 20, 1)'],
        ['rgba(255, 159, 64, 0.5)', 'rgba(255, 159, 64, 1)'],
        ['rgba(153, 102, 255, 0.5)', 'rgba(153, 102, 255, 1)']
    ];
    
    // Create datasets for buildings
    $buildingDatasets = [];
    foreach ($buildings as $index => $building) {
        $buildingData = [];
        foreach ($years as $year) {
            $buildingData[] = $buildingYearlyData[$year][$building] ?? 0;
        }
        
        $buildingDatasets[] = [
            'label' => $building,
            'data' => $buildingData,
            'backgroundColor' => $colors[$index % count($colors)][0],
            'borderColor' => $colors[$index % count($colors)][1],
            'fill' => true
        ];
    }
    
    // Create datasets for types
    $typeDatasets = [];
    foreach ($types as $index => $type) {
        $typeData = [];
        foreach ($years as $year) {
            $typeData[] = $typeYearlyData[$year][$type] ?? 0;
        }
        
        $typeDatasets[] = [
            'label' => $type,
            'data' => $typeData,
            'backgroundColor' => $colors[$index % count($colors)][0],
            'borderColor' => $colors[$index % count($colors)][1],
            'fill' => true
        ];
    }

    // Save charts as PNG files
    try {
        // Create charts directory if it doesn't exist
        $chartsDir = __DIR__ . '/charts';
        if (!file_exists($chartsDir)) {
            mkdir($chartsDir, 0755, true);
        }

        // Save building chart
        $buildingChartPath = $chartsDir . '/building_chart_' . $company['company_id'] . '.png';
        $_SESSION['building_chart_path'] = $buildingChartPath;

        // Save type chart
        $typeChartPath = $chartsDir . '/type_chart_' . $company['company_id'] . '.png';
        $_SESSION['type_chart_path'] = $typeChartPath;

    } catch (Exception $e) {
        error_log("Failed to save chart images: " . $e->getMessage());
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        'float-slow': 'float 3s ease-in-out infinite',
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
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: transparent;
            z-index: 0;
        }
        .relative-z {
            position: relative;
            z-index: 1;
        }
        .gegga { width: 0; }
        .snurra { filter: url(#gegga); }
        .stopp1 { stop-color: #f700a8; }
        .stopp2 { stop-color: #ff8000; }
        .halvan {
            animation: Snurra1 10s infinite linear;
            stroke-dasharray: 180 800;
            fill: none;
            stroke: url(#gradient);
            stroke-width: 23;
            stroke-linecap: round;
        }
        .strecken {
            animation: Snurra1 3s infinite linear;
            stroke-dasharray: 26 54;
            fill: none;
            stroke: url(#gradient);
            stroke-width: 23;
            stroke-linecap: round;
        }
        @keyframes Snurra1 {
            0% { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: -403px; }
        }
    </style>
</head>
<body class="bg-space-black min-h-screen flex flex-col font-poppins text-gray-100">
    
    <!-- Add Particles Container -->
    <div id="particles-js" class="fixed inset-0 z-0 pointer-events-none"></div>

    <!-- Wrap existing content in relative-z -->
    <div class="relative-z">
        <!-- Header with glass effect -->
        <header class="neo-glass px-6 py-4 shadow-lg relative relative-z border-b border-neon-blue/20">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <nav class="container mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0 relative z-10">
                <a href="index.php" class="flex items-center space-x-3 hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-bolt text-white text-3xl animate-float"></i>
                    <img src="images/ener.png" alt="energAIze" class="h-10 w-auto">
                </a>
                <div class="neo-glass px-6 py-2 rounded-full">
                    <div class="text-white">Export Report</div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="container mx-auto flex-grow px-4 py-12" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
            <div class="max-w-6xl mx-auto neo-glass rounded-2xl p-8 cyber-border transform transition-all duration-500"
                 x-show="shown"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                
                <!-- Report Header -->
                <div class="flex items-center space-x-6 mb-8 border-b border-gray-200/20 pb-8">
                    <div class="p-4 neo-glass cyber-border rounded-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-file-word text-5xl bg-gradient-to-r from-neon-blue via-deep-purple to-cyber-pink bg-clip-text text-transparent animate-float-slow"></i>
                    </div>
                    <div class="transform hover:-translate-y-1 transition-transform duration-300">
                        <h2 class="text-4xl font-bold text-white relative group">
                            Report Preview
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-neon-blue via-deep-purple to-cyber-pink group-hover:w-full transition-all duration-300"></span>
                        </h2>
                        <p class="text-gray-400 mt-2 text-lg tracking-wide">
                            Review and export your energy analysis report
                            <span class="inline-block ml-2 text-neon-blue animate-pulse">•</span>
                        </p>
                    </div>
                </div>

                <!-- Charts Container -->
                <div id="reportContent" class="space-y-8">
                    <!-- Preview Container -->
                    <div class="neo-glass p-6 rounded-xl mb-8">
                        <h3 class="text-2xl font-bold text-white mb-4">Document Preview</h3>
                        <div class="bg-white rounded-lg p-6">
                            <!-- Company Logo -->
                            <div class="flex justify-center mb-8">
                                <?php if (!empty($company['company_logo']) && file_exists($company['company_logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($company['company_logo']); ?>" alt="Company Logo" class="h-20 object-contain">
                                <?php else: ?>
                                    <img src="images/ener.png" alt="energAIze" class="h-20">
                                <?php endif; ?>
                            </div>
                            
                            <!-- Title -->
                            <div class="text-center mb-8">
                                <h1 class="text-4xl font-bold text-gray-800">ENERGY DEMAND</h1>
                                <h1 class="text-4xl font-bold text-gray-800">ANALYSIS REPORT</h1>
                            </div>

                            <!-- Decorative Line -->
                            <div class="text-center mb-8">
                                <div class="text-gray-400 text-2xl">____________________</div>
                            </div>

                            <!-- Company Details -->
                            <div class="text-center mb-8">
                                <p class="text-gray-600 italic mb-2">Generated for:</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($company['company_name'] ?? 'COMPANY NAME NOT FOUND'); ?></p>
                            </div>

                            <!-- Building Details -->
                            <div class="text-center mb-8">
                                <p class="text-gray-600 italic mb-2">Building Gross Area:</p>
                                <p class="text-lg text-gray-800"><?php echo number_format($company['gross_area'], 2); ?> m²</p>
                            </div>

                            <!-- Date -->
                            <div class="text-center mb-8">
                                <p class="text-gray-600 italic mb-2">Report Generation Date:</p>
                                <p class="text-lg text-gray-800"><?php echo date('F d, Y'); ?></p>
                            </div>

                            <!-- Prepared By -->
                            <div class="text-center mb-8">
                                <p class="text-gray-600 italic mb-2">Prepared by:</p>
                                <p class="text-xl font-bold text-gray-800">EnergAIze Analytics Team</p>
                            </div>

                            <!-- Confidential Mark -->
                            <div class="text-center mb-8">
                                <p class="text-red-600 italic text-sm">CONFIDENTIAL DOCUMENT</p>
                            </div>

                            <!-- Page Break Indicator -->
                            <div class="border-b border-gray-200 my-8"></div>

                            <!-- Executive Summary -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-4">Executive Summary</h2>
                                <p class="text-gray-700 text-justify">
                                    <?php echo nl2br(htmlspecialchars($executiveSummary)); ?>
                                </p>
                            </div>

                            <!-- Footer Preview -->
                            <div class="text-center text-gray-500 text-xs italic mt-12 pt-4 border-t">
                                energAIze Analytics | Confidential Document | Page 1 of {NUMPAGES}
                            </div>
                        </div>
                    </div>

                    <!-- Building Chart Container -->
                    <div class="neo-glass p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-white mb-4">Building Energy Demand Analysis</h2>
                        <p class="text-gray-300 mb-4">
                            This comprehensive visualization presents the temporal evolution of energy consumption patterns across various buildings within the facility. 
                            The stacked area chart format enables easy identification of both individual building contributions and the total energy demand over time. 
                            Each colored section represents a specific building's energy consumption, allowing facility managers to track performance and identify potential anomalies or trends in usage patterns. 
                            The year-over-year comparison facilitates the assessment of energy efficiency improvements and helps in identifying buildings that may require attention or optimization measures. 
                            This analysis is particularly valuable for strategic planning, resource allocation, and implementing targeted energy conservation measures across different buildings.
                        </p>
                        <div class="w-full h-[400px] bg-white rounded-lg p-4" id="buildingChartContainer">
                            <canvas id="buildingChart"></canvas>
                        </div>
                    </div>

                    <!-- Type Chart Container -->
                    <div class="neo-glass p-6 rounded-xl">
                        <h2 class="text-xl font-bold text-white mb-4">Energy Demand by Type Analysis</h2>
                        <p class="text-gray-300 mb-4">
                            This detailed analysis breaks down the facility's energy consumption by different types of usage, providing crucial insights into how energy is utilized across various applications. 
                            The stacked area representation allows for easy visualization of both the absolute consumption of each energy type and its relative proportion to the total demand over time. 
                            This breakdown is essential for understanding the distribution of energy usage across different categories such as HVAC, lighting, equipment, and other operational needs. 
                            By tracking these patterns over time, facility managers can identify opportunities for optimization, assess the impact of energy-saving initiatives, and make data-driven decisions about future energy management strategies. 
                            The visualization also helps in understanding seasonal variations and long-term trends in different types of energy consumption, which is crucial for planning and implementing targeted efficiency improvements.
                        </p>
                        <div class="w-full h-[400px] bg-white rounded-lg p-4" id="typeChartContainer">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Export Button -->
                <div class="mt-8 flex justify-center">
                    <button onclick="exportReport()" 
                            class="neo-glass bg-gradient-to-r from-neon-blue via-deep-purple to-cyber-pink text-white font-bold py-4 px-8 rounded-xl hover:shadow-[0_0_20px_rgba(0,240,255,0.5)] transition-all duration-300 group">
                        <span class="flex items-center justify-center gap-3">
                            <i class="fas fa-file-export text-xl group-hover:rotate-12 transition-transform"></i>
                            Export to DOCX
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-2"></i>
                        </span>
                    </button>
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
    </div>



    <!-- Add Particles.js Configuration -->
    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 100, density: { enable: true, value_area: 800 } },
                color: { value: ['#00F0FF', '#6E00FF', '#FF2D55', '#39FF14'] },
                shape: { type: 'circle' },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: { enable: true, speed: 1, opacity_min: 0.1, sync: false }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: { enable: true, speed: 2, size_min: 0.1, sync: false }
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
                    attract: { enable: true, rotateX: 600, rotateY: 1200 }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'grab' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                },
                modes: {
                    grab: { distance: 140, line_linked: { opacity: 0.5 } },
                    push: { particles_nb: 4 }
                }
            },
            retina_detect: true
        });
    </script>

    <!-- Existing export script -->
    <script>
        // Building Stacked Area Chart
        const buildingCtx = document.getElementById('buildingChart').getContext('2d');
        const buildingChart = new Chart(buildingCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($years ?? []); ?>,
                datasets: <?php echo json_encode($buildingDatasets ?? []); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false, // Disable animation for better PNG export
                plugins: {
                    title: {
                        display: true,
                        text: 'Building Energy Demand Over Time',
                        color: '#1a1a1a',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#1a1a1a',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Energy Demand (kWh)',
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#1a1a1a'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Year',
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#1a1a1a'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });

        // Type Stacked Area Chart
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        const typeChart = new Chart(typeCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($years ?? []); ?>,
                datasets: <?php echo json_encode($typeDatasets ?? []); ?>
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false, // Disable animation for better PNG export
                plugins: {
                    title: {
                        display: true,
                        text: 'Energy Demand by Type Over Time',
                        color: '#1a1a1a',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#1a1a1a',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Energy Demand (kWh)',
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#1a1a1a'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Year',
                            color: '#1a1a1a'
                        },
                        ticks: {
                            color: '#1a1a1a'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                }
            }
        });

        // Function to convert charts to PNG
        async function exportReport() {
            try {
                // Get the canvas elements
                const buildingCanvas = document.getElementById('buildingChart');
                const typeCanvas = document.getElementById('typeChart');

                // Force a white background
                const getChartImage = (canvas) => {
                    const newCanvas = document.createElement('canvas');
                    newCanvas.width = canvas.width;
                    newCanvas.height = canvas.height;
                    const ctx = newCanvas.getContext('2d');
                    
                    // Fill white background
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, newCanvas.width, newCanvas.height);
                    
                    // Draw original canvas
                    ctx.drawImage(canvas, 0, 0);
                    
                    return newCanvas.toDataURL('image/png', 1.0);
                };

                // Get chart images with white background
                const buildingChartImage = getChartImage(buildingCanvas);
                const typeChartImage = getChartImage(typeCanvas);

                // Create form data
                const formData = new FormData();
                formData.append('buildingChart', buildingChartImage);
                formData.append('typeChart', typeChartImage);

                // Send request
                const response = await fetch('create_docx.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) throw new Error('Failed to generate report');

                // Handle the response
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Energy_Demand_Report.docx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);

            } catch (error) {
                console.error('Export failed:', error);
                alert('Failed to export report. Please try again.');
            }
        }
    </script>

    <!-- Add this JavaScript after your chart initialization -->
    <script>
    async function saveChartsAsPNG() {
        try {
            // Get the chart containers
            const buildingContainer = document.getElementById('buildingChartContainer');
            const typeContainer = document.getElementById('typeChartContainer');

            // Wait for charts to render completely
            await new Promise(resolve => setTimeout(resolve, 500));

            // Use html2canvas to capture the charts
            const buildingCanvas = await html2canvas(buildingContainer, {
                backgroundColor: '#FFFFFF',
                scale: 2, // Increase quality
                logging: false,
                useCORS: true
            });

            const typeCanvas = await html2canvas(typeContainer, {
                backgroundColor: '#FFFFFF',
                scale: 2, // Increase quality
                logging: false,
                useCORS: true
            });

            // Convert to base64
            const buildingImage = buildingCanvas.toDataURL('image/png');
            const typeImage = typeCanvas.toDataURL('image/png');

            // Send to PHP for saving
            const formData = new FormData();
            formData.append('building_chart', buildingImage);
            formData.append('type_chart', typeImage);
            formData.append('company_id', '<?php echo $company['company_id']; ?>');

            const response = await fetch('save_charts.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Failed to save charts');
            }

            console.log('Charts saved successfully');
        } catch (error) {
            console.error('Error saving charts:', error);
        }
    }

    // Call the function after charts are initialized
    document.addEventListener('DOMContentLoaded', () => {
        // Wait for charts to be fully rendered
        setTimeout(saveChartsAsPNG, 1000);
    });
    </script>
</body>
</html>