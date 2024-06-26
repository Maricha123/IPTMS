<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Include database connection
include 'db.php';

// Check if the report ID is provided in the URL
if (isset($_GET['report_id'])) {
    // Get the report ID from the URL
    $reportID = $_GET['report_id'];

    // Retrieve the report details from the database
    $query = "SELECT works, week_number FROM reports WHERE report_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $reportID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = "Report_Week_" . $row['week_number'] . ".pdf"; // Example: Report_Week_1.pdf
        $fileContent = $row['works'];

        // Include TCPDF library
        require_once('tcpdf/tcpdf.php');

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Student Dashboard');
        $pdf->SetTitle('Weekly Report');
        $pdf->SetSubject('Generated Report');
        $pdf->SetKeywords('TCPDF, PDF, report, student');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'Weekly Report', 'Generated by Student Dashboard', array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Write the content
        $htmlContent = '<h1>Weekly Report</h1>';
        $htmlContent .= '<h2>Week ' . htmlspecialchars($row['week_number']) . '</h2>';
        $htmlContent .= '<p>' . nl2br(htmlspecialchars($fileContent)) . '</p>';

        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Output the PDF
        $pdf->Output($fileName, 'D');
    } else {
        echo "Report not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Please provide report ID.";
}

// Close the database connection
$conn->close();
?>
