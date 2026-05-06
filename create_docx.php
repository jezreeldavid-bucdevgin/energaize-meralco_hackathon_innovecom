<?php
session_start();
require_once 'vendor/autoload.php';
include 'db_connect.php';

// Get the latest company profile
$query = "SELECT * FROM company_profiles ORDER BY company_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$company = mysqli_fetch_assoc($result);

// Get the executive summary from session
$executiveSummary = $_SESSION['executive_summary'] ?? 'No executive summary available.';

// Create new PHPWord instance
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Add styles
$phpWord->addTitleStyle(1, ['size' => 24, 'bold' => true, 'alignment' => 'center']);
$phpWord->addTitleStyle(2, ['size' => 16, 'bold' => true]);

// Create section with margins
$section = $phpWord->addSection([
    'marginLeft' => 1440,  // 1 inch in twips
    'marginRight' => 1440,
    'marginTop' => 1440,
    'marginBottom' => 1440
]);

// Title Page
if (!empty($company['company_logo']) && file_exists($company['company_logo'])) {
    try {
        $section->addImage(
            $company['company_logo'],
            [
                'width' => 200,
                'height' => 100,
                'alignment' => 'center',
                'wrappingStyle' => 'inline'
            ]
        );
    } catch (Exception $e) {
        error_log("Failed to add company logo: " . $e->getMessage());
    }
}

$section->addText('ENERGY DEMAND', ['size' => 24, 'bold' => true], ['alignment' => 'center']);
$section->addText('ANALYSIS REPORT', ['size' => 24, 'bold' => true], ['alignment' => 'center']);
$section->addText('____________________', ['size' => 16], ['alignment' => 'center']);

// Company Details
$section->addTextBreak(2);
$section->addText('Generated for:', ['italic' => true], ['alignment' => 'center']);
$section->addText($company['company_name'] ?? 'COMPANY NAME NOT FOUND', ['bold' => true, 'size' => 16], ['alignment' => 'center']);

// Building Details
$section->addTextBreak(2);
$section->addText('Building Gross Area:', ['italic' => true], ['alignment' => 'center']);
$section->addText(number_format($company['gross_area'], 2) . ' m²', ['size' => 14], ['alignment' => 'center']);

// Date
$section->addTextBreak(2);
$section->addText('Report Generation Date:', ['italic' => true], ['alignment' => 'center']);
$section->addText(date('F d, Y'), ['size' => 14], ['alignment' => 'center']);

// Prepared By
$section->addTextBreak(2);
$section->addText('Prepared by:', ['italic' => true], ['alignment' => 'center']);
$section->addText('EnergAIze Analytics Team', ['bold' => true, 'size' => 16], ['alignment' => 'center']);

// Confidential Mark
$section->addTextBreak(2);
$section->addText('CONFIDENTIAL DOCUMENT', ['italic' => true, 'color' => 'FF0000'], ['alignment' => 'center']);

// Add page break
$section->addPageBreak();

// Executive Summary Section
$section->addPageBreak();
$section->addTitle('Executive Summary', 1);
// Use the executive summary from session
$textrun = $section->addTextRun();
$textrun->addText($executiveSummary, ['size' => 11]);

// Add page break before charts
$section->addPageBreak();

// Add Building Chart Section
$section->addTitle('Building Energy Demand Analysis', 1);

// Add Building Chart Description
$buildingDescription = "This comprehensive visualization presents the temporal evolution of energy consumption patterns across various buildings within the facility. "
    . "The stacked area chart format enables easy identification of both individual building contributions and the total energy demand over time. "
    . "Each colored section represents a specific building's energy consumption, allowing facility managers to track performance and identify potential anomalies or trends in usage patterns. "
    . "The year-over-year comparison facilitates the assessment of energy efficiency improvements and helps in identifying buildings that may require attention or optimization measures. "
    . "This analysis is particularly valuable for strategic planning, resource allocation, and implementing targeted energy conservation measures across different buildings.";

$section->addText($buildingDescription, ['size' => 11], ['spacing' => 120]);
$section->addTextBreak(1);

// Add Building Chart
if (isset($_SESSION['building_chart_path']) && file_exists($_SESSION['building_chart_path'])) {
    try {
        $section->addImage(
            $_SESSION['building_chart_path'],
            [
                'width' => 500,
                'height' => 300,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'wrappingStyle' => 'inline'
            ]
        );
    } catch (Exception $e) {
        error_log("Failed to add building chart: " . $e->getMessage());
        $section->addText('Chart could not be generated', ['italic' => true, 'color' => 'FF0000']);
    }
} else {
    error_log("Building chart file not found: " . ($_SESSION['building_chart_path'] ?? 'path not set'));
    $section->addText('Chart could not be generated', ['italic' => true, 'color' => 'FF0000']);
}

$section->addPageBreak();

// Add Type Chart Section
$section->addTitle('Energy Demand by Type Analysis', 1);

// Add Type Chart Description
$typeDescription = "This detailed analysis breaks down the facility's energy consumption by different types of usage, providing crucial insights into how energy is utilized across various applications. "
    . "The stacked area representation allows for easy visualization of both the absolute consumption of each energy type and its relative proportion to the total demand over time. "
    . "This breakdown is essential for understanding the distribution of energy usage across different categories such as HVAC, lighting, equipment, and other operational needs. "
    . "By tracking these patterns over time, facility managers can identify opportunities for optimization, assess the impact of energy-saving initiatives, and make data-driven decisions about future energy management strategies. "
    . "The visualization also helps in understanding seasonal variations and long-term trends in different types of energy consumption, which is crucial for planning and implementing targeted efficiency improvements.";

$section->addText($typeDescription, ['size' => 11], ['spacing' => 120]);
$section->addTextBreak(1);

// Add Type Chart
if (isset($_SESSION['type_chart_path']) && file_exists($_SESSION['type_chart_path'])) {
    try {
        $section->addImage(
            $_SESSION['type_chart_path'],
            [
                'width' => 500,
                'height' => 300,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                'wrappingStyle' => 'inline'
            ]
        );
    } catch (Exception $e) {
        error_log("Failed to add type chart: " . $e->getMessage());
        $section->addText('Chart could not be generated', ['italic' => true, 'color' => 'FF0000']);
    }
} else {
    error_log("Type chart file not found: " . ($_SESSION['type_chart_path'] ?? 'path not set'));
    $section->addText('Chart could not be generated', ['italic' => true, 'color' => 'FF0000']);
}

// Add footer
$footer = $section->addFooter();
$footer->addText(
    'energAIze Analytics | Confidential Document | Page {PAGE} of {NUMPAGES}',
    ['size' => 8, 'italic' => true],
    ['alignment' => 'center']
);

try {
    // Save the document
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    
    // Set headers for download
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment;filename="Energy_Demand_Report.docx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    
    // Clear any previous output
    ob_clean();
    
    // Save document
    $objWriter->save('php://output');
    exit;
} catch (Exception $e) {
    error_log("Failed to generate DOCX: " . $e->getMessage());
    header('HTTP/1.1 500 Internal Server Error');
    echo "Failed to generate report. Please try again.";
    exit;
}
?> 