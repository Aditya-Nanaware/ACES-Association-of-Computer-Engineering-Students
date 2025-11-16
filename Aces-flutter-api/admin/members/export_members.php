<?php
header('Content-Type: application/json');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="filename.csv"');

// Include database connection
include "../../config/db_connection.php";

if (isset($_GET['type'])) {
    $exportType = $_GET['type'];
} else {
    echo json_encode(["success" => false, "message" => "No export type specified."]);
    exit();
}

// Fetch members from the database, including the skills column
$query = "SELECT name, role, academic_year, skills FROM committee_members";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Failed to fetch members from the database."]);
    exit();
}

$members = [];
while ($row = mysqli_fetch_assoc($result)) {
    $members[] = $row;
}

if ($exportType === 'csv') {
    exportCSV($members);
} elseif ($exportType === 'pdf') {
    exportPDF($members);
} else {
    echo json_encode(["success" => false, "message" => "Invalid export type specified."]);
    exit();
}

function exportCSV($members)
{
    // Define the filename
    $filename = "members_" . date('Y-m-d_H-i-s') . ".csv";

    // Set headers for CSV file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add header row
    fputcsv($output, ['Name', 'Role', 'Academic Year', 'Skills']);

    // Add member data rows
    foreach ($members as $member) {
        fputcsv($output, [
            $member['name'],
            $member['role'],
            $member['academic_year'] ?? 'N/A',
            $member['skills'] ?? 'N/A'
        ]);
    }

    fclose($output);
}

function exportPDF($members)
{
    // Include the FPDF library
    require("fpdf/fpdf.php"); // Make sure the path is correct

    // Create PDF object
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Add header
    $pdf->Cell(40, 10, 'Name');
    $pdf->Cell(40, 10, 'Role');
    $pdf->Cell(40, 10, 'Academic Year');
    $pdf->Cell(40, 10, 'Skills');
    $pdf->Ln();

    // Add member data
    foreach ($members as $member) {
        $pdf->Cell(40, 10, $member['name']);
        $pdf->Cell(40, 10, $member['role']);
        $pdf->Cell(40, 10, $member['academic_year'] ?? 'N/A');
        $pdf->Cell(40, 10, $member['skills'] ?? 'N/A');
        $pdf->Ln();
    }

    // Output the PDF
    $filename = "members_" . date('Y-m-d_H-i-s') . ".pdf";
    $pdf->Output('D', $filename);
    exit();
}
?>