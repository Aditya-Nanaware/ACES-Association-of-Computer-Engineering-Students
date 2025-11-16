<?php
session_start();
include "../includes/db.php";
include "header.php"; // Includes HTML head, Bootstrap, and navbar

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
$result = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<h2 class="mb-4">Hi, <?php echo htmlspecialchars($user['name']); ?>! 📅 Available Events</h2>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>

<?php while ($event = $result->fetch_assoc()): ?>
    <div class="card mb-3">
        <?php if (!empty($event['image'])): ?>
            <img src="../uploads/<?php echo $event['image']; ?>" class="card-img-top"
                style="max-height: 250px; object-fit: cover;" alt="Event Poster">
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?php echo $event['title']; ?></h5>
            <p class="card-text"><?php echo $event['description']; ?></p>
            <p class="card-text">
                <small class="text-muted"><?php echo $event['event_date']; ?> @ <?php echo $event['location']; ?></small>
            </p>
            <a href="register_event.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">Register</a>
        </div>
    </div>

<?php endwhile; ?>

</div> <!-- Close container from header.php -->
</body>

</html>