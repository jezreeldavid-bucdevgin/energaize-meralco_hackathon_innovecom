<?php
header('Content-Type: application/json');

try {
    if (!isset($_FILES['excel_file']) || !isset($_POST['company_id'])) {
        throw new Exception('Missing required data');
    }

    $company_id = $_POST['company_id'];
    $excel_file = $_FILES['excel_file']['tmp_name'];

    // Execute Python script
    $command = escapeshellcmd("python3 model.py " . escapeshellarg($excel_file) . " " . escapeshellarg($company_id));
    $output = shell_exec($command);

    // Parse the Python output (assuming it returns JSON)
    $analysis_results = json_decode($output, true);

    if ($analysis_results === null) {
        throw new Exception('Error processing Excel file');
    }

    // Return success response with analysis results
    echo json_encode([
        'success' => true,
        'analysis' => $analysis_results['summary'],
        // Add any other analysis data you want to return
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
