// Get the chart data from PHP
const chartData = <?php echo $chartData; ?>;

// Process data for Chart.js
const years = [...new Set(chartData.map(item => item.year))];
const buildings = [...new Set(chartData.map(item => item.building))];
const types = [...new Set(chartData.map(item => item.type))];

// ... (rest of the chart creation code from analytics.php) 