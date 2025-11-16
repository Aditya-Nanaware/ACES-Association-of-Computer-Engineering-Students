<?php
// budget_api.php
header('Content-Type: application/json');
session_start();
include "../../config/db_connection.php";

// // Simple admin check - modify according to your auth method
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
//     http_response_code(401);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit();
// }

// Helper function to send response
function sendResponse($data, $status = 200)
{
    http_response_code($status);
    echo json_encode($data);
    exit();
}

// Get the action parameter to decide the API function
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_events':
        getEvents($conn);
        break;
    case 'get_budget':
        $event_id = intval($_GET['event_id'] ?? 0);
        if ($event_id <= 0) {
            sendResponse(['error' => 'Invalid event_id'], 400);
        }
        getBudget($conn, $event_id);
        break;
    default:
        sendResponse(['error' => 'Invalid action'], 400);
}

function getEvents($conn)
{
    $events = [];
    $result = $conn->query("SELECT id, title FROM events ORDER BY event_date DESC");
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    sendResponse(['events' => $events]);
}

function getBudget($conn, $event_id)
{
    // Get budget
    $stmt = $conn->prepare("SELECT b.budget_amount, e.title FROM event_budgets b JOIN events e ON b.event_id = e.id WHERE b.event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $budget_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$budget_data) {
        sendResponse(['error' => 'No budget found for this event'], 404);
    }

    // Get expenses
    $stmt = $conn->prepare("SELECT id, description, amount, expense_date FROM event_expenses WHERE event_id = ? ORDER BY expense_date ASC");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $expenses_result = $stmt->get_result();

    $expenses = [];
    $total_spent = 0;
    while ($row = $expenses_result->fetch_assoc()) {
        $expenses[] = $row;
        $total_spent += $row['amount'];
    }
    $stmt->close();

    $remaining = $budget_data['budget_amount'] - $total_spent;

    sendResponse([
        'event_title' => $budget_data['title'],
        'budget_amount' => $budget_data['budget_amount'],
        'total_spent' => $total_spent,
        'remaining_amount' => $remaining,
        'expenses' => $expenses,
    ]);
}
?>