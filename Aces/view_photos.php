<?php
include "includes/db.php";
include "student/header.php"; // Or use a public header

$event_id = $_GET['event_id'] ?? null;
$event = null;
$photos = [];

if ($event_id) {
    // Fetch event details
    $stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();

    // Fetch photos
    $stmt = $conn->prepare("SELECT photo FROM event_photos WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $photos = $stmt->get_result();
}
?>

<div class="container mt-5">
    <?php if ($event): ?>
        <h2 class="mb-4 text-center">📸 Photos from: <?php echo htmlspecialchars($event['title']); ?></h2>

        <?php if ($photos->num_rows > 0): ?>
            <div class="row">
                <?php while ($p = $photos->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card shadow-sm">
                            <img src="uploads/events/<?php echo $p['photo']; ?>" class="card-img-top"
                                style="object-fit: cover; height: 200px;">
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No photos uploaded yet for this event.</div>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-danger text-center">Invalid event selected.</div>
    <?php endif; ?>
</div>

</body>

</html>