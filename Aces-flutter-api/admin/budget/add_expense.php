<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");  // Adjust this for security in production
header("Access-Control-Allow-Methods: POST");

include "../../config/db_connection.php";

// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    !isset($data['event_id']) ||
    !isset($data['description']) ||
    !isset($data['amount']) ||
    !isset($data['expense_date'])
) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

// Sanitize and assign
$event_id = intval($data['event_id']);
$description = trim($data['description']);
$amount = floatval($data['amount']);
$expense_date = $data['expense_date']; // Expecting YYYY-MM-DD

// Prepare and execute insert
$stmt = $conn->prepare("INSERT INTO event_expenses (event_id, description, amount, expense_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isds", $event_id, $description, $amount, $expense_date);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Expense added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add expense"]);
}
?>