<?php
include '../../config/db_connection.php';
header('Content-Type: application/json');

if (!isset($_POST['id'], $_POST['title'], $_POST['description'], $_POST['location'], $_POST['event_date'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$location = $_POST['location'];
$event_date = $_POST['event_date'];
$imagePath = null;

// Get current image from DB
$getImageQuery = $conn->prepare("SELECT image FROM events WHERE id = ?");
$getImageQuery->bind_param("i", $id);
$getImageQuery->execute();
$getImageQuery->bind_result($existingImage);
$getImageQuery->fetch();
$getImageQuery->close();

$imagePath = $existingImage;

// If a new image is uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $file = $_FILES['image'];
    $targetDir = "../../uploads/event_images/";

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate a new unique file name
    $uniqueName = time() . "_" . basename($file['name']);
    $targetFile = $targetDir . $uniqueName;

    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        echo json_encode(['status' => 'error', 'message' => 'Uploaded file is not a valid image.']);
        exit;
    }

    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload new image.']);
        exit;
    }

    // Delete the old image if it exists and is not empty
    if (!empty($existingImage) && file_exists($targetDir . $existingImage)) {
        unlink($targetDir . $existingImage);  // Delete using the relative path stored in DB
    }

    $imagePath = $uniqueName;  // Save only the filename (not the full path) in the DB
}

// Update query
$updateQuery = $conn->prepare("UPDATE events SET title = ?, description = ?, location = ?, event_date = ?, image = ? WHERE id = ?");
$updateQuery->bind_param("sssssi", $title, $description, $location, $event_date, $imagePath, $id);

if ($updateQuery->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Event updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update event.']);
}

$updateQuery->close();
$conn->close();
?>