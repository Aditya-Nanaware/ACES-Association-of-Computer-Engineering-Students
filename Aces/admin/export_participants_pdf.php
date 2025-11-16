<?php
session_start();
include "../includes/db.php";
require("fpdf/fpdf.php"); // Make sure the path is correct

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied.");
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    die("Invalid event.");
}

// Get event info
$event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$event_title = $event ? $event['title'] : "Event";

// Get participant list
$stmt = $conn->prepare("SELECT u.name, u.email FROM users u 
                        JOIN event_registrations r ON u.id = r.user_id 
                        WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$participants = $stmt->get_result();

// Start FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, "Participants List - " . $event_title, 0, 1, 'C');
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, '#', 1);
$pdf->Cell(70, 10, 'Name', 1);
$pdf->Cell(100, 10, 'Email', 1);
$pdf->Ln();

// Table Rows
$pdf->SetFont('Arial', '', 12);
$count = 1;
while ($row = $participants->fetch_assoc()) {
    $pdf->Cell(10, 10, $count++, 1);
    $pdf->Cell(70, 10, $row['name'], 1);
    $pdf->Cell(100, 10, $row['email'], 1);
    $pdf->Ln();
}

$pdf->Output("D", "{$event_title}_participants.pdf");
exit();
