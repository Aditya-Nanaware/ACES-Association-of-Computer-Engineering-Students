<?php
session_start();
include "../includes/db.php";
include "header.php"; // Bootstrap navbar & container

// ✅ Check if user is admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $image = null;

    // ✅ Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_dir = "../uploads/";
        $target_path = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image = $image_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $event_date, $location, $image);

    if ($stmt->execute()) {
        $success = "Event added successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>

<h2 class="mb-4">➕ Add New Event</h2>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- ✅ Updated form with file upload -->
<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Event Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" required>
    </div>

    <div class="col-12">
        <label class="form-label">Event Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Event Date</label>
        <input type="date" name="event_date" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Event Poster (optional)</label>
        <input type="file" name="image" class="form-control">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Event</button>
        <a href="manage_events.php" class="btn btn-secondary">← Back to Event List</a>
    </div>
</form>

</div> <!-- close container from header -->
</body>

</html>