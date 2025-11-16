<?php
session_start();
include "../../config/db_connection.php";

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Handle budget submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $event_id = $_POST['event_id'] ?? null;
    $budget = $_POST['budget_amount'] ?? null;

    if ($event_id && $budget) {
        // Check if budget already exists for this event
        $check = $conn->prepare("SELECT * FROM event_budgets WHERE event_id = ?");
        $check->bind_param("i", $event_id);
        $check->execute();
        $existing = $check->get_result();

        if ($existing->num_rows > 0) {
            $response['message'] = "Budget already set for this event.";
        } else {
            // Insert new budget
            $stmt = $conn->prepare("INSERT INTO event_budgets (event_id, budget_amount) VALUES (?, ?)");
            $stmt->bind_param("id", $event_id, $budget);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "✅ Budget set successfully!";
            } else {
                $response['message'] = "❌ Failed to set budget.";
            }
        }
    } else {
        $response['message'] = "❌ Missing required parameters.";
    }
} else {
    $response['message'] = "❌ Invalid request method.";
}

// Output response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>