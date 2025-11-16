<?php
header("Content-Type: application/json");
include 'config/db_connection.php'; // Make sure this contains your DB config

// Allow Cross-Origin Requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Debugging: Output user details (be cautious with production code)
            // echo json_encode($user); // Uncomment for debugging purposes
            if (password_verify($password, $user['password'])) {
                $response['status'] = 'success';
                $response['data'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Incorrect password.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Admin not found or unauthorized.';
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>