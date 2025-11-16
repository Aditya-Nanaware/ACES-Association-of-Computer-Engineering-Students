<?php
session_start();
include "../includes/db.php";

require "../vendor/autoload.php"; // For PDF export using FPDF
require '../vendor/setasign/fpdf/fpdf.php';


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$year = $_GET['year'] ?? '';
$format = $_GET['format'] ?? 'csv';

if (!$year || !in_array($format, ['csv', 'pdf'])) {
    die("Invalid parameters.");
}

$stmt = $conn->prepare("SELECT name, role, year, branch, skills FROM committee_members WHERE academic_year = ?");
$stmt->bind_param("s", $year);
$stmt->execute();
$result = $stmt->get_result();

if ($format === 'csv') {
    // Send CSV headers
    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=committee_$year.csv");

    $output = fopen("php://output", "w");
    fputcsv($output, ['Name', 'Role', 'Year', 'Branch', 'Skills']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['name'], $row['role'], $row['year'], $row['branch'], $row['skills']]);
    }

    fclose($output);
    exit();
}

if ($format === 'pdf') {
    class PDF extends \FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'ACES Committee Members - ' . $_GET['year'], 0, 1, 'C');
            $this->Ln(5);
        }
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(230, 230, 230);
    $pdf->Cell(40, 10, 'Name', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Role', 1, 0, 'C', true);
    $pdf->Cell(15, 10, 'Year', 1, 0, 'C', true);
    $pdf->Cell(35, 10, 'Branch', 1, 0, 'C', true);
    $pdf->Cell(65, 10, 'Skills', 1, 1, 'C', true);

    $pdf->SetFont('Arial', '', 11);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['name'], 1);
        $pdf->Cell(35, 10, $row['role'], 1);
        $pdf->Cell(15, 10, $row['year'], 1);
        $pdf->Cell(35, 10, $row['branch'], 1);
        $pdf->Cell(65, 10, $row['skills'], 1);
        $pdf->Ln();
    }

    $pdf->Output("D", "committee_$year.pdf");
    exit();
}
