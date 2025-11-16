<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "../../config/db_connection.php";

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔸 Fetch all POST data
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $skills = $_POST['skills'];
    $academic_year = $_POST['academic_year']; // ✅ Add/Edit Academic Year here

    // 🔸 Fetch existing photo name
    $stmt = $conn->prepare("SELECT photo FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($existingImage);
    $stmt->fetch();
    $stmt->close();

    $photo = $existingImage; // Default to existing photo

    // 🔸 Check if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $newPhoto = 'members_' . uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = "../../uploads/members/" . $newPhoto;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Delete the old image if it exists
            if (!empty($existingImage)) {
                $oldImagePath = "../../uploads/members/" . $existingImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $photo = $newPhoto;
        } else {
            $response['message'] = "Photo upload failed.";
            echo json_encode($response);
            exit;
        }
    }

    // 🔸 Prepare and execute update query
    $sql = "UPDATE committee_members SET name=?, role=?, year=?, branch=?, skills=?, academic_year=?, photo=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $name, $role, $year, $branch, $skills, $academic_year, $photo, $id);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Member updated successfully.";
    } else {
        $response['message'] = "Failed to update member: " . $stmt->error;
    }
} else {
    $response['message'] = "Invalid request.";
}

echo json_encode($response);
?>