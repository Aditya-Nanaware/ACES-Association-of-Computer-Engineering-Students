<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "../../config/db_connection.php";

$response = [];

$sql = "SELECT id, title, description, event_date, location, image FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[] = [
            "id" => $row['id'],
            "title" => $row['title'],
            "description" => $row['description'],
            "event_date" => $row['event_date'],
            "location" => $row['location'],
            "image" => $row['image'] ? "http://10.210.246.254/Aces-flutter-api/uploads/event_images/" . $row['image'] : null
        ];
    }

    $response['success'] = true;
    $response['count'] = count($events);
    $response['event'] = $events;

} else {
    $response['success'] = false;
    $response['message'] = "No events found.";
}

echo json_encode($response);
?>