<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include "../../config/db_connection.php";

// Check method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Only POST requests allowed"]);
    exit();
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['event_id'], $input['budget_amount'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit();
}

$event_id = $input['event_id'];
$budget = $input['budget_amount'];

// Check if budget already exists
$check = $conn->prepare("SELECT id FROM event_budgets WHERE event_id = ?");
$check->bind_param("i", $event_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Budget already set for this event."]);
    exit();
}

// Insert budget
$stmt = $conn->prepare("INSERT INTO event_budgets (event_id, budget_amount) VALUES (?, ?)");
$stmt->bind_param("id", $event_id, $budget);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Budget set successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to set budget"]);
}
?>