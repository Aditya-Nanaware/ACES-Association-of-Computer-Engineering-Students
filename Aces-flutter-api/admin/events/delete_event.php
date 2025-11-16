<?php
include "../../config/db_connection.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $event_id = $data->id ?? null;

    if ($event_id) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete event.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Event ID is missing.']);
    }
}
?>