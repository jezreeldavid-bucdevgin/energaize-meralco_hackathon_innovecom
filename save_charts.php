<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (!isset($_POST['building_chart']) || !isset($_POST['type_chart']) || !isset($_POST['company_id'])) {
        throw new Exception('Missing required data');
    }

    // Create charts directory if it doesn't exist
    $chartsDir = __DIR__ . '/charts';
    if (!file_exists($chartsDir)) {
        mkdir($chartsDir, 0755, true);
    }

    // Function to save base64 image
    function saveBase64Image($base64Data, $filepath) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
        if ($data === false) {
            throw new Exception('Invalid base64 data');
        }
        if (file_put_contents($filepath, $data) === false) {
            throw new Exception('Failed to save image');
        }
        return true;
    }

    // Save building chart
    $buildingChartPath = $chartsDir . '/building_chart_' . $_POST['company_id'] . '.png';
    saveBase64Image($_POST['building_chart'], $buildingChartPath);
    $_SESSION['building_chart_path'] = $buildingChartPath;

    // Save type chart
    $typeChartPath = $chartsDir . '/type_chart_' . $_POST['company_id'] . '.png';
    saveBase64Image($_POST['type_chart'], $typeChartPath);
    $_SESSION['type_chart_path'] = $typeChartPath;

    echo json_encode(['success' => true, 'message' => 'Charts saved successfully']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    error_log('Chart save error: ' . $e->getMessage());
} 