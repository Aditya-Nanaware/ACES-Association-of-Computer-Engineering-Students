<?php
session_start();
include "../includes/db.php";
include "header.php"; // Load navbar and container

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<h2 class="mb-4">🛠️ Manage Events</h2>

<a href="add_event.php" class="btn btn-success mb-3">➕ Add New Event</a>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Location</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $event['title']; ?></td>
                    <td><?php echo $event['event_date']; ?></td>
                    <td><?php echo $event['location']; ?></td>
                    <td><?php echo $event['description']; ?></td>
                    <td>
                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <a href="delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger"
                            onclick="return confirm('Are you sure you want to delete this event?');">🗑️ Delete</a>
                        <a href="view_participants.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-info">👥
                            View</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</div> <!-- close container -->
</body>

</html>