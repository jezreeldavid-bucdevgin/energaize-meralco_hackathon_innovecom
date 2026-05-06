<?php
require_once 'vendor/autoload.php';
include 'db_connect.php';
include 'execute_python.php';

// Get the latest uploaded Excel file path from the database
$query = "SELECT excel_file FROM company_profiles ORDER BY company_id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$excelPath = $row['excel_file'];

// Execute Python script and get the output
$pythonOutput = executePythonScript('model.py', $excelPath);
$executiveSummary = $pythonOutput; // Use Python output as executive summary

echo $executiveSummary;

// try {
//     $phpWord = new \PhpOffice\PhpWord\PhpWord();
//     $phpWord->setDefaultFontName('Arial');
//     $phpWord->setDefaultFontSize(11);

//     // Add document properties
//     $properties = $phpWord->getDocInfo();
//     $properties->setCreator('Energy Demand System');
//     $properties->setTitle('Energy Demand Report');
//     $properties->setDescription('Automated Energy Demand Analysis Report');
//     $properties->setCompany('Your Company Name');

//     // Create Title Page Section
//     $titleSection = $phpWord->addSection([
//         'marginLeft' => 1000,
//         'marginRight' => 1000,
//         'marginTop' => 1000,
//         'marginBottom' => 1000
//     ]);

//     // Add company logo if needed
//     // $titleSection->addImage('path/to/logo.png', ['width' => 200, 'alignment' => 'center']);
//     // $titleSection->addTextBreak(2);

//     // Title Page Content
//     $titleSection->addText('ENERGY DEMAND', ['bold' => true, 'size' => 36], ['alignment' => 'center']);
//     $titleSection->addText('ANALYSIS REPORT', ['bold' => true, 'size' => 36], ['alignment' => 'center']);
//     $titleSection->addTextBreak(8);

//     $titleSection->addText('Prepared by:', ['size' => 12], ['alignment' => 'center']);
//     $titleSection->addText('Energy Demand System', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
//     $titleSection->addTextBreak(4);

//     $titleSection->addText('Generated on:', ['size' => 12], ['alignment' => 'center']);
//     $titleSection->addText(date('F j, Y'), ['bold' => true, 'size' => 14], ['alignment' => 'center']);

//     // Add page break
//     $titleSection->addPageBreak();

//     // Executive Summary Section
//     $execSection = $phpWord->addSection([
//         'marginLeft' => 1000,
//         'marginRight' => 1000,
//         'marginTop' => 1000,
//         'marginBottom' => 1000
//     ]);

//     // Executive Summary Header
//     $execSection->addText('Executive Summary', ['bold' => true, 'size' => 20], ['alignment' => 'left']);
//     $execSection->addTextBreak(2);

//     // Add introduction paragraph
//     $execSection->addText('This report provides a comprehensive analysis of energy demand patterns and consumption trends based on the provided data. The following summary highlights key findings and recommendations.', ['size' => 11]);
//     $execSection->addTextBreak(2);

//     // Split and add the executive summary content
//     $paragraphs = explode("\n", $executiveSummary);
//     foreach ($paragraphs as $paragraph) {
//         if (trim($paragraph) !== '') {
//             $execSection->addText(trim($paragraph), ['size' => 11], ['spacing' => 1.15]);
//             $execSection->addTextBreak(1);
//         }
//     }

//     // Add footer to all pages
//     $footer = $execSection->addFooter();
//     $footer->addText('Page {PAGE} of {NUMPAGES}', ['size' => 8], ['alignment' => 'center']);

//     // Save the document
//     $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//     $tempFile = tempnam(sys_get_temp_dir(), 'report');
//     $objWriter->save($tempFile);
// } catch (Exception $e) {
//     die('Error creating document: ' . $e->getMessage());
// }

// // Send the file to the browser
// header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
// header('Content-Disposition: attachment;filename="Energy_Demand_Report.docx"');
// header('Cache-Control: max-age=0');

// readfile($tempFile);
// unlink($tempFile);
?> 