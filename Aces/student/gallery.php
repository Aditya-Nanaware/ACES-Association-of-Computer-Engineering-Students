<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$selected_event = $_GET['event_id'] ?? null;
$today = date('Y-m-d');

// Get completed events
$events = $conn->query("SELECT id, title FROM events WHERE event_date < '$today' ORDER BY event_date DESC");

// Fetch photos for selected event
$photos = [];
if ($selected_event) {
    $stmt = $conn->prepare("SELECT photo FROM event_photos WHERE event_id = ?");
    $stmt->bind_param("i", $selected_event);
    $stmt->execute();
    $photos = $stmt->get_result();
}
?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">📷 Event Photo Gallery</h2>

    <!-- Dropdown -->
    <form method="GET" class="mb-4 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <select name="event_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Completed Event --</option>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <option value="<?php echo $event['id']; ?>" <?php if ($event['id'] == $selected_event)
                               echo 'selected'; ?>>
                            <?php echo $event['title']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
    </form>

    <!-- Show photos if an event is selected -->
    <?php if ($selected_event && $photos->num_rows > 0): ?>
        <div class="row">
            <?php while ($p = $photos->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm">
                        <img src="../uploads/events/<?php echo $p['photo']; ?>" class="card-img-top"
                            style="object-fit: cover; height: 200px;">
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php elseif ($selected_event): ?>
        <div class="alert alert-warning text-center">No photos uploaded yet for this event.</div>
    <?php endif; ?>
</div>

</body>

</html>