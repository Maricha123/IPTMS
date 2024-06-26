<?php
session_start();

// Ensure the user is logged in before accessing the page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Include database connection
include 'db.php';

// Check if the logbook ID is provided in the URL
if (isset($_GET['logbook_id'])) {
    // Get the logbook ID from the URL
    $logbookID = $_GET['logbook_id'];

    // Retrieve the logbook details from the database
    $query = "SELECT date, workspace, uploaded_at FROM logbooks WHERE logbook_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $logbookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileName = "logbook_" . $row['date'] . ".pdf"; // You can adjust the file name as needed
        $fileContent = $row['workspace'];

        // Include TCPDF library
        require_once('tcpdf/tcpdf.php');

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Student');
        $pdf->SetTitle('Logbook Entry');
        $pdf->SetSubject('Generated Logbook Entry');
        $pdf->SetKeywords('TCPDF, PDF, logbook, student');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'Logbook Entry', 'Generated by Student', array(0,64,255), array(0,64,128));
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
        $htmlContent = '<h1>Contents</h1>';
        $htmlContent .= '<h2>Date: ' . htmlspecialchars($row['date']) . '</h2>';
        $htmlContent .= '<h2>Uploaded At: ' . htmlspecialchars($row['uploaded_at']) . '</h2>';
        $htmlContent .= '<p>' . nl2br(htmlspecialchars($fileContent)) . '</p>';

        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Output the PDF
        $pdf->Output($fileName, 'D');
    } else {
        echo "Logbook not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Please provide logbook ID.";
}

// Close the database connection
$conn->close();
?>
