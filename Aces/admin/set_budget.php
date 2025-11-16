<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = null;

// Handle budget submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $budget = $_POST['budget_amount'];

    // Check if budget already exists for this event
    $check = $conn->prepare("SELECT * FROM event_budgets WHERE event_id = ?");
    $check->bind_param("i", $event_id);
    $check->execute();
    $existing = $check->get_result();

    if ($existing->num_rows > 0) {
        $error = "Budget already set for this event.";
    } else {
        $stmt = $conn->prepare("INSERT INTO event_budgets (event_id, budget_amount) VALUES (?, ?)");
        $stmt->bind_param("id", $event_id, $budget);

        if ($stmt->execute()) {
            $success = "✅ Budget set successfully!";
        } else {
            $error = "❌ Failed to set budget.";
        }
    }
}

// Get list of events
$events = $conn->query("SELECT id, title FROM events ORDER BY event_date DESC");
?>

<h2 class="mb-4">💰 Set Budget for Event</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="row g-3" style="max-width: 600px;">
    <div class="col-12">
        <label class="form-label">Select Event</label>
        <select name="event_id" class="form-select" required>
            <option value="">-- Select Event --</option>
            <?php while ($e = $events->fetch_assoc()): ?>
                <option value="<?php echo $e['id']; ?>"><?php echo $e['title']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Total Budget (₹)</label>
        <input type="number" step="0.01" name="budget_amount" class="form-control" required>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Set Budget</button>
        <a href="dashboard.php" class="btn btn-secondary">← Back</a>
    </div>
</form>

</div>
</body>

</html>