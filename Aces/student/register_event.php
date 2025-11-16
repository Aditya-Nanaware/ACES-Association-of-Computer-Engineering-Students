<?php
session_start();
include "../includes/db.php";

// Redirect if not logged in or not a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$event_id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? 'register'; // default is register

if (!$event_id) {
    $msg = "Invalid event.";
    header("Location: events.php?msg=" . urlencode($msg));
    exit();
}

if ($action === 'register') {
    // Check if already registered
    $stmt = $conn->prepare("SELECT * FROM event_registrations WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $user_id, $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "You have already registered for this event.";
    } else {
        $stmt = $conn->prepare("INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $event_id);
        $msg = $stmt->execute() ? "Successfully registered!" : "Something went wrong.";
    }
} elseif ($action === 'unregister') {
    // Remove registration
    $stmt = $conn->prepare("DELETE FROM event_registrations WHERE user_id = ? AND event_id = ?");
    $stmt->bind_param("ii", $user_id, $event_id);
    $msg = $stmt->execute() ? "You have been unregistered from the event." : "Failed to unregister.";
} else {
    $msg = "Unknown action.";
}

// Redirect back to student events page with a message
header("Location: events.php?msg=" . urlencode($msg));
exit();
