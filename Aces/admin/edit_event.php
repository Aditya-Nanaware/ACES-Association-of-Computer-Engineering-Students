<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    header("Location: manage_events.php");
    exit();
}

// Fetch existing event data
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$event = $stmt->get_result()->fetch_assoc();

if (!$event) {
    echo "<div class='alert alert-danger'>Event not found.</div>";
    exit();
}

// Handle form update
$success = $error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, location = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $description, $event_date, $location, $event_id);

    if ($stmt->execute()) {
        $success = "✅ Event updated successfully!";
        // Refresh data
        $event = ['title' => $title, 'description' => $description, 'event_date' => $event_date, 'location' => $location];
    } else {
        $error = "❌ Failed to update event.";
    }
}
?>

<h2 class="mb-4">✏️ Edit Event</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="row g-3" style="max-width: 600px;">
    <div class="col-md-6">
        <label class="form-label">Event Title</label>
        <input type="text" name="title" class="form-control" required
            value="<?php echo htmlspecialchars($event['title']); ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" required
            value="<?php echo htmlspecialchars($event['location']); ?>">
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4"
            required><?php echo htmlspecialchars($event['description']); ?></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Event Date</label>
        <input type="date" name="event_date" class="form-control" required value="<?php echo $event['event_date']; ?>">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">💾 Update Event</button>
        <a href="manage_events.php" class="btn btn-secondary">← Back</a>
    </div>
</form>

</div>
</body>

</html>