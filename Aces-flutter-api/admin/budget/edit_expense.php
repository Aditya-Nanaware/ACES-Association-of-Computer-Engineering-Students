<?php
header("Content-Type: application/json");
include "../../config/db_connection.php";

$response = ['status' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Check required fields
    if (
        isset($data['id']) && isset($data['description']) &&
        isset($data['amount']) && isset($data['expense_date'])
    ) {
        $id = intval($data['id']);
        $description = trim($data['description']);
        $amount = floatval($data['amount']);
        $expense_date = trim($data['expense_date']);

        $stmt = $conn->prepare("UPDATE event_expenses SET description = ?, amount = ?, expense_date = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $description, $amount, $expense_date, $id);

        if ($stmt->execute()) {
            $response['status'] = true;
            $response['message'] = "Expense updated successfully!";
        } else {
            $response['message'] = "Failed to update expense.";
        }
    } else {
        $response['message'] = "Missing required fields.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
