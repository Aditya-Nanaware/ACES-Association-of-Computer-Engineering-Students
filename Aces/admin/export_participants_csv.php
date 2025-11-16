<?php
session_start();
include "../includes/db.php";

// Check admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("Access denied");
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    die("Event ID is missing.");
}

// Get event title (optional, for filename)
$event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$event_title = $event ? preg_replace("/[^a-zA-Z0-9]/", "_", $event['title']) : "event_participants";

// Get participants
$stmt = $conn->prepare("SELECT u.name, u.email FROM users u 
                        JOIN event_registrations r ON u.id = r.user_id 
                        WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

// Set headers to force download
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename={$event_title}_participants.csv");

$output = fopen('php://output', 'w');
fputcsv($output, ['Name', 'Email']); // header row

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['name'], $row['email']]);
}

fclose($output);
exit();
