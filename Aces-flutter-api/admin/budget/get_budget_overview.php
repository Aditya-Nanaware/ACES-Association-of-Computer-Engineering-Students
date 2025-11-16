<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include DB connection
include "../../config/db_connection.php";

// Check if DB is set up
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

// Get all events with budgets
$query = "
    SELECT e.id, e.title, b.budget_amount
    FROM event_budgets b
    JOIN events e ON b.event_id = e.id
    ORDER BY e.event_date DESC
";

$result = $conn->query($query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Query failed"]);
    exit();
}

$overview = [];
$total_budget = 0;
$total_spent_all = 0;
$total_remaining = 0;

while ($event = $result->fetch_assoc()) {
    $event_id = $event['id'];

    // Get total spent for the event
    $stmt = $conn->prepare("SELECT SUM(amount) AS total_spent FROM event_expenses WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $spent_result = $stmt->get_result()->fetch_assoc();
    $total_spent = $spent_result['total_spent'] ?? 0;

    $remaining = $event['budget_amount'] - $total_spent;

    // Update totals
    $total_budget += $event['budget_amount'];
    $total_spent_all += $total_spent;
    $total_remaining += $remaining;

    $overview[] = [
        "event_id" => $event_id,
        "title" => $event['title'],
        "budget_amount" => round((float) $event['budget_amount'], 2),
        "total_spent" => round((float) $total_spent, 2),
        "remaining" => round((float) $remaining, 2),
    ];
}

// Final response
$response = [
    "success" => true,
    "overview" => $overview,
    "totals" => [
        "total_budget" => round((float) $total_budget, 2),
        "total_spent" => round((float) $total_spent_all, 2),
        "total_remaining" => round((float) $total_remaining, 2)
    ]
];

echo json_encode($response);
