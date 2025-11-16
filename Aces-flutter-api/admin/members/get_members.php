<?php
header('Content-Type: application/json');
include "../../config/db_connection.php";

$response = [];

try {
    // Get academic_year from the GET parameter (if provided)
    $year = $_GET['academic_year'] ?? ''; // Use the GET parameter if it exists

    // If academic_year is provided, filter the results based on it
    if (!empty($year)) {
        $stmt = $conn->prepare("SELECT * FROM committee_members WHERE academic_year = ? ORDER BY id ASC");
        $stmt->bind_param("s", $year); // Bind the academic_year parameter
    } else {
        // If no academic_year is provided, fetch all members
        $stmt = $conn->prepare("SELECT * FROM committee_members ORDER BY id ASC");
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Prepare the array to store members data
    $members = [];

    // Fetch the results and store them in the $members array
    while ($row = $result->fetch_assoc()) {
        $members[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'role' => $row['role'],
            'year' => $row['year'],
            'branch' => $row['branch'],
            'skills' => $row['skills'],
            'academic_year' => $row['academic_year'],
            'photo' => $row['photo'] ?: 'default_avatar.png' // Fallback photo
        ];
    }

    // Return the response with success and member data
    echo json_encode([
        'success' => true,
        'count' => count($members), // ✅ This line adds the count
        'members' => $members
    ]);

} catch (Exception $e) {
    // If there is an error, return a failure response
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching members: ' . $e->getMessage()
    ]);
}
?>