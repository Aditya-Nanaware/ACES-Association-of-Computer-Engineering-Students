<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = null;

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $files = $_FILES['photos'];

    if (!empty($files['name'][0])) {
        $upload_dir = "../uploads/events/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($files['name'] as $index => $name) {
            $filename = time() . "_" . basename($name);
            $target = $upload_dir . $filename;
            $tmp_name = $files['tmp_name'][$index];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                if (move_uploaded_file($tmp_name, $target)) {
                    $stmt = $conn->prepare("INSERT INTO event_photos (event_id, photo) VALUES (?, ?)");
                    $stmt->bind_param("is", $event_id, $filename);
                    $stmt->execute();
                }
            }
        }

        $success = "✅ Photos uploaded successfully!";
    } else {
        $error = "❌ Please select at least one photo.";
    }
}

// Fetch all events
$events = $conn->query("SELECT id, title FROM events ORDER BY event_date DESC");
?>

<h2 class="mb-4">📸 Upload Event Photos</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="row g-3" style="max-width: 600px;">
    <div class="col-12">
        <label class="form-label">Select Event</label>
        <select name="event_id" class="form-select" required>
            <option value="">-- Select Event --</option>
            <?php
            mysqli_data_seek($events, 0); // rewind to start
            while ($e = $events->fetch_assoc()): ?>
                <option value="<?php echo $e['id']; ?>" <?php if (isset($event_id) && $event_id == $e['id']) echo 'selected'; ?>>
                    <?php echo $e['title']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Upload Photos</label>
        <input type="file" name="photos[]" class="form-control" multiple accept="image/png, image/jpeg">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Upload Photos</button>
    </div>
</form>

<?php
// Display uploaded photos for this event
if (isset($event_id) && $success) {
    $stmt = $conn->prepare("SELECT photo FROM event_photos WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $photos = $stmt->get_result();

    if ($photos->num_rows > 0): ?>
        <hr class="my-5">
        <h4 class="mb-3">🖼️ Uploaded Photos</h4>
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
    <?php else: ?>
        <div class="alert alert-warning mt-4">No photos uploaded yet for this event.</div>
    <?php endif;
}
?>

</div>
</body>
</html>
