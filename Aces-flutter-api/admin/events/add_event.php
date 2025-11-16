<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include "../../config/db_connection.php";

// Check if all required fields exist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $event_date = $_POST['event_date'] ?? '';
    $location = $_POST['location'] ?? '';
    $image = null;

    if (!$title || !$description || !$event_date || !$location) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit;
    }

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);

        // Change target directory to save inside event_images folder
        $target_dir = "../../uploads/event_images/";  // Change the folder here
        $target_path = $target_dir . $image_name;

        // Create the folder if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create the folder with proper permissions
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = $image_name;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload image"]);
            exit;
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $event_date, $location, $image);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Event added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>