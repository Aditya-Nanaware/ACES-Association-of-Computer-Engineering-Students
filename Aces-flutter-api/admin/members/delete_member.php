<?php
header('Content-Type: application/json');

include "../../config/db_connection.php";

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Member ID is required']);
        exit;
    }

    // Get photo file name
    $stmt = $conn->prepare("SELECT photo FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    // Delete member
    $stmt = $conn->prepare("DELETE FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        // Delete photo from server
        if ($photo && file_exists("../uploads/members/$photo")) {
            unlink("../uploads/members/$photo");
        }

        echo json_encode(['success' => true, 'message' => 'Member deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete member']);
    }

    // Handle GET request
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Member ID is required']);
        exit;
    }

    // Get photo file name
    $stmt = $conn->prepare("SELECT photo FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($photo);
    $stmt->fetch();
    $stmt->close();

    // Delete member
    $stmt = $conn->prepare("DELETE FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        // Delete photo from server
        if ($photo && file_exists("../uploads/members/$photo")) {
            unlink("../uploads/members/$photo");
        }

        echo json_encode(['success' => true, 'message' => 'Member deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete member']);
    }

    // Invalid request method
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>