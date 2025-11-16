<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("config/db_connection.php");  // Your DB config

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (empty($admin_id) || empty($name) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        exit;
    }

    $upload_success = true;
    $profile_image = null;

    // Remove size restriction, now it will accept any file size
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create uploads dir if not exists
        }

        $image_tmp = $_FILES["profile_image"]["tmp_name"];
        $image_ext = pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION);
        $profile_image = uniqid("admin_", true) . "." . strtolower($image_ext);
        $target_file = $target_dir . $profile_image;

        // Optional: Check for allowed extensions (no file size limit)
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($image_ext, $allowed_ext)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid image format']);
            exit;
        }

        // Now no size restriction, so skip the file size check
        if (!move_uploaded_file($image_tmp, $target_file)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            exit;
        }
    }

    if ($profile_image) {
        $sql = "UPDATE users SET name = ?, email = ?, profile_photo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $profile_image, $admin_id);
    } else {
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $admin_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
    }
}
?>