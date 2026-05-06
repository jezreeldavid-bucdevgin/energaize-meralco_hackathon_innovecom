<?php
include 'db_connect.php';

// Function to handle file upload
function handleFileUpload($file, $targetDir, $allowedTypes) {
    $fileName = basename($file["name"]);
    $targetPath = $targetDir . time() . '_' . $fileName; // Add timestamp to prevent duplicate names
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Check if file type is allowed
    if (!in_array($fileType, $allowedTypes)) {
        return [false, "Sorry, only " . implode(", ", $allowedTypes) . " files are allowed."];
    }

    // Move uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        return [true, $targetPath];
    } else {
        return [false, "Sorry, there was an error uploading your file."];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate company name
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        if (empty($company_name)) {
            throw new Exception("Company name is required");
        }

        // Validate gross area
        $gross_area = mysqli_real_escape_string($conn, $_POST['gross_area']);
        if (empty($gross_area) || !is_numeric($gross_area) || $gross_area < 0) {
            throw new Exception("Valid gross area is required");
        }

        // Handle logo upload
        $allowedImageTypes = ["jpg", "jpeg", "png", "gif"];
        $logoResult = handleFileUpload(
            $_FILES["company_logo"],
            "uploads/",
            $allowedImageTypes
        );
        if (!$logoResult[0]) {
            throw new Exception($logoResult[1]);
        }
        $logo_path = $logoResult[1];

        // Handle excel upload
        $allowedExcelTypes = ["xlsx", "xls"];
        $excelResult = handleFileUpload(
            $_FILES["excel_file"],
            "uploads/",
            $allowedExcelTypes
        );
        if (!$excelResult[0]) {
            throw new Exception($excelResult[1]);
        }
        $excel_path = $excelResult[1];

        // Prepare and execute SQL statement
        $stmt = $conn->prepare("INSERT INTO company_profiles (company_name, company_logo, company_excel, gross_area) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $company_name, $logo_path, $excel_path, $gross_area);

        if ($stmt->execute()) {
            // Success message and redirect to output.php
            echo "<script>
                alert('Company profile created successfully!');
                window.location.href = 'output.php';
            </script>";
        } else {
            throw new Exception("Error inserting record: " . $conn->error);
        }

        $stmt->close();

    } catch (Exception $e) {
        // Error message - also update this redirect to return to the form page
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href = 'landing.php';
        </script>";
    }
}

// Create the necessary directories if they don't exist
$directories = ['uploads/logos', 'uploads/excel'];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

$conn->close();
?>
