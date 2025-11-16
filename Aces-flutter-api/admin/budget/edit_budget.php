<?php
header('Content-Type: application/json');

// Include DB connection
require '../../config/db_connection.php'; // Make sure this path is correct

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Retrieve and validate inputs
$data = json_decode(file_get_contents("php://input"), true);

$eventId = isset($data['event_id']) ? intval($data['event_id']) : 0;
$newBudget = isset($data['budget_amount']) ? floatval($data['budget_amount']) : -1;

if ($eventId <= 0 || $newBudget < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Update the budget
$sql = "UPDATE event_budgets SET budget_amount = ? WHERE event_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL preparation failed']);
    exit;
}

$stmt->bind_param("di", $newBudget, $eventId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Budget updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update budget']);
}

$stmt->close();
$conn->close();
?>