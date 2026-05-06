<?php
include 'db_connect.php';

// Fetch data from company_profile and process the Excel BLOB
$query = "SELECT company_excel FROM company_profiles";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Process Excel BLOB data
require 'vendor/autoload.php'; // Make sure you have PhpSpreadsheet installed
$data = [];

if ($row && $row['company_excel']) {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($row['company_excel']);
    $worksheet = $spreadsheet->getActiveSheet();
    
    foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $rowData = [];
        
        // Assuming Excel columns are in order: building, type, year, demand
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        
        if ($rowData[0] !== 'building') { // Skip header row
            $data[] = [
                'building' => $rowData[0],
                'type' => $rowData[1],
                'year' => $rowData[2],
                'demand' => $rowData[3]
            ];
        }
    }
}

// Convert PHP array to JSON for JavaScript use
$chartData = json_encode($data);
?>

<!-- Add these canvas elements -->
<div style="width: 100%; max-width: 800px; margin: 20px auto;">
    <canvas id="buildingChart" style="height: 400px;"></canvas>
</div>
<div style="width: 100%; max-width: 800px; margin: 20px auto;">
    <canvas id="typeChart" style="height: 400px;"></canvas>
</div>

<script>
// Get the chart data from PHP
const chartData = <?php echo $chartData; ?>;

// Process data for Chart.js
const years = [...new Set(chartData.map(item => item.year))];
const buildings = [...new Set(chartData.map(item => item.building))];
const types = [...new Set(chartData.map(item => item.type))];

// Define a cyberpunk color palette
const chartColors = {
    backgrounds: [
        'rgba(0, 240, 255, 0.3)',    // neon-blue
        'rgba(110, 0, 255, 0.3)',    // deep-purple
        'rgba(255, 45, 85, 0.3)',    // cyber-pink
        'rgba(57, 255, 20, 0.3)',    // electric-green
        'rgba(255, 128, 0, 0.3)',    // cyber-orange
        'rgba(255, 0, 255, 0.3)',    // magenta
    ],
    borders: [
        'rgba(0, 240, 255, 1)',      // neon-blue
        'rgba(110, 0, 255, 1)',      // deep-purple
        'rgba(255, 45, 85, 1)',      // cyber-pink
        'rgba(57, 255, 20, 1)',      // electric-green
        'rgba(255, 128, 0, 1)',      // cyber-orange
        'rgba(255, 0, 255, 1)',      // magenta
    ]
};

// Building Chart - Stacked Area Chart
const buildingChart = new Chart(
    document.getElementById('buildingChart'),
    {
        type: 'line',
        data: {
            labels: years,
            datasets: buildings.map((building, index) => ({
                label: building,
                data: years.map(year => {
                    const buildingDemand = chartData
                        .filter(item => item.year === year && item.building === building)
                        .reduce((sum, item) => sum + parseFloat(item.demand), 0);
                    return buildingDemand;
                }),
                backgroundColor: chartColors.backgrounds[index % chartColors.backgrounds.length],
                borderColor: chartColors.borders[index % chartColors.borders.length],
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            backgroundColor: 'white',
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#333',
                        callback: function(value) {
                            return value.toLocaleString() + ' kWh';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Energy Demand (kWh)',
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Poppins'
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#333'
                    },
                    title: {
                        display: true,
                        text: 'Year',
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Poppins'
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Energy Demand by Building',
                    color: '#333',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Poppins'
                    },
                    padding: 20
                },
                tooltip: {
                    mode: 'index',
                    backgroundColor: 'rgba(30, 30, 30, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(0, 240, 255, 0.3)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toLocaleString() + ' kWh';
                            return label;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: {
                            family: 'Poppins',
                            size: 12
                        },
                        padding: 15
                    }
                }
            }
        }
    }
);

// Type Chart - Stacked Area Chart
const typeChart = new Chart(
    document.getElementById('typeChart'),
    {
        type: 'line',
        data: {
            labels: years,
            datasets: types.map((type, index) => ({
                label: type,
                data: years.map(year => {
                    const typeDemand = chartData
                        .filter(item => item.year === year && item.type === type)
                        .reduce((sum, item) => sum + parseFloat(item.demand), 0);
                    return typeDemand;
                }),
                backgroundColor: chartColors.backgrounds[index % chartColors.backgrounds.length],
                borderColor: chartColors.borders[index % chartColors.borders.length],
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            backgroundColor: 'white',
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#333',
                        callback: function(value) {
                            return value.toLocaleString() + ' kWh';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Energy Demand (kWh)',
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Poppins'
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#333'
                    },
                    title: {
                        display: true,
                        text: 'Year',
                        color: '#333',
                        font: {
                            size: 14,
                            weight: 'bold',
                            family: 'Poppins'
                        }
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Energy Demand by Type',
                    color: '#333',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Poppins'
                    },
                    padding: 20
                },
                tooltip: {
                    mode: 'index',
                    backgroundColor: 'rgba(30, 30, 30, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(0, 240, 255, 0.3)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toLocaleString() + ' kWh';
                            return label;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: {
                            family: 'Poppins',
                            size: 12
                        },
                        padding: 15
                    }
                }
            }
        }
    }
);
</script> 