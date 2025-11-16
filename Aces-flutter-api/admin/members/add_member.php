<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
include "../../config/db_connection.php";

$response = ["status" => "error", "message" => "Something went wrong"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $year = $_POST['year'] ?? '';
    $branch = $_POST['branch'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $academic_year = $_POST['academic_year'] ?? '';
    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $target = "../../uploads/members/" . $photo_name;
        $ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $photo_name;
        } else {
            $response['message'] = "Invalid photo format or upload error.";
            echo json_encode($response);
            exit;
        }
    }

    // ✅ Correct INSERT with academic_year
    $stmt = $conn->prepare("INSERT INTO committee_members (name, role, year, branch, skills, academic_year, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $role, $year, $branch, $skills, $academic_year, $photo);

    if ($stmt->execute()) {
        $response = ["status" => "success", "message" => "Member added successfully"];
    } else {
        $response['message'] = "Database insertion failed.";
    }
}

echo json_encode($response);
