<?php
session_start();
include "includes/db.php";
include "student/header.php"; // Load shared header with Bootstrap and navbar

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Fetch upcoming events (limit to 3)
$events_result = $conn->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 3");

// Fetch events registered by student
$stmt = $conn->prepare("SELECT e.title, e.event_date, e.location 
                        FROM events e
                        JOIN event_registrations r ON e.id = r.event_id
                        WHERE r.user_id = ? ORDER BY e.event_date ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$registered_result = $stmt->get_result();
?>

<h2 class="mb-4">Welcome, <?php echo htmlspecialchars($user['name']); ?>! 🎓</h2>

<h4>📢 Upcoming Events</h4>
<?php if ($events_result->num_rows == 0): ?>
    <div class="alert alert-warning">No upcoming events.</div>
<?php else: ?>
    <?php while ($event = $events_result->fetch_assoc()): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo $event['title']; ?></h5>
                <p class="card-text"><?php echo $event['description']; ?></p>
                <p class="card-text">
                    <small class="text-muted"><?php echo $event['event_date']; ?> @ <?php echo $event['location']; ?></small>
                </p>
                <a href="student/register_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary me-2">Register</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<!-- Registered Events -->
<hr>
<h4>📝 Your Registered Events</h4>
<?php if ($registered_result->num_rows == 0): ?>
    <div class="alert alert-info">You haven't registered for any events yet.</div>
<?php else: ?>
    <ul class="list-group">
        <?php while ($reg = $registered_result->fetch_assoc()): ?>
            <li class="list-group-item">
                <strong><?php echo $reg['title']; ?></strong><br>
                <small><?php echo $reg['event_date']; ?> @ <?php echo $reg['location']; ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>

</div> <!-- Close container -->
</body>

</html>