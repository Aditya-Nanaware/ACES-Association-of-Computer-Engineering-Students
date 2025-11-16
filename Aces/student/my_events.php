<?php
session_start();
include "../includes/db.php";
include "header.php"; // Includes HTML <head>, Bootstrap, navbar

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT e.id AS event_id, e.title, e.event_date, e.location 
                        FROM events e
                        JOIN event_registrations r ON e.id = r.event_id
                        WHERE r.user_id = ?");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class="mb-4">📝 My Registered Events</h2>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-info">
        You haven't registered for any events yet.
    </div>
<?php else: ?>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <strong><?php echo $row['title']; ?></strong><br>
                    <small><?php echo $row['event_date']; ?> @ <?php echo $row['location']; ?></small>
                </div>
                <a href="register_event.php?id=<?php echo $row['event_id']; ?>&action=unregister" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to unregister from this event?');">
                    ❌ Unregister
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

<?php endif; ?>

</div> <!-- Close container from header.php -->
</body>

</html>