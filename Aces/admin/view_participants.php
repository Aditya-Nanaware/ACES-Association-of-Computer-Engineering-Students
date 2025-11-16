<?php
session_start();
include "../includes/db.php";
include "header.php";

// Only allow admins
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    echo "<div class='alert alert-danger'>Invalid event ID.</div></div></body></html>";
    exit();
}

// Get event info
$event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();

// Get registered users
$stmt = $conn->prepare("SELECT u.name, u.email FROM users u 
                        JOIN event_registrations r ON u.id = r.user_id 
                        WHERE r.event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$participants = $stmt->get_result();
?>

<h2 class="mb-4">👥 Registered Students for: <span class="text-primary"><?php echo $event['title']; ?></span></h2>

<?php if ($participants->num_rows == 0): ?>
    <div class="alert alert-warning">No students have registered for this event yet.</div>
<?php else: ?>
    <p><strong>Total Registrations:</strong> <?php echo $participants->num_rows; ?></p>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; ?>
            <?php while ($row = $participants->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $count++; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="manage_events.php" class="btn btn-secondary mt-3">← Back to Events</a>
<a href="export_participants_csv.php?id=<?php echo $event_id; ?>" class="btn btn-success mb-3">⬇️ Export CSV</a>
<a href="export_participants_pdf.php?id=<?php echo $event_id; ?>" class="btn btn-danger mb-3">🧾 Export PDF</a>


</div>
</body>

</html>