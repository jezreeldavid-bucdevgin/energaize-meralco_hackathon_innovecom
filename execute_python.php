<?php
function executePythonScript($scriptName, $excelPath) {
    // Get the absolute path to the Python script
    $scriptPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $scriptName;
    
    // Ensure the Excel path is valid
    if (!file_exists($excelPath)) {
        error_log("Excel file not found: $excelPath");
        return json_encode([
            'success' => false,
            'error' => 'Excel file not found'
        ]);
    }

    // Path to Python interpreter
    $pythonPath = "python";  // or "python3" depending on your system

    // Build and execute the command
    $command = sprintf(
        '%s "%s" "%s" 2>&1',
        escapeshellcmd($pythonPath),
        escapeshellarg($scriptPath),
        escapeshellarg($excelPath)
    );

    // Execute and capture both output and errors
    $output = shell_exec($command);

    // Log the raw output for debugging
    error_log("Python script output: " . $output);

    // Check if the output is valid JSON
    $jsonOutput = json_decode($output, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Invalid JSON output from Python script: " . json_last_error_msg());
        return json_encode([
            'success' => false,
            'error' => 'Invalid output from Python script',
            'raw_output' => $output
        ]);
    }

    return $output;
}
?> 