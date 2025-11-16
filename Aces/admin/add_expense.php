<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];

    $stmt = $conn->prepare("INSERT INTO event_expenses (event_id, description, amount, expense_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $event_id, $description, $amount, $expense_date);

    if ($stmt->execute()) {
        $success = "✅ Expense added successfully!";
    } else {
        $error = "❌ Failed to add expense.";
    }
}

// Fetch events with budgets
$events = $conn->query("
    SELECT e.id, e.title 
    FROM events e
    JOIN event_budgets b ON e.id = b.event_id
    ORDER BY e.event_date DESC
");
?>

<h2 class="mb-4">🧾 Add Expense for Event</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="row g-3" style="max-width: 600px;">
    <div class="col-12">
        <label class="form-label">Event</label>
        <select name="event_id" class="form-select" required>
            <option value="">-- Select Event --</option>
            <?php while ($e = $events->fetch_assoc()): ?>
                <option value="<?php echo $e['id']; ?>"><?php echo $e['title']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control" placeholder="Venue booking, snacks, printing, etc."
            required>
    </div>

    <div class="col-6">
        <label class="form-label">Amount (₹)</label>
        <input type="number" step="0.01" name="amount" class="form-control" required>
    </div>

    <div class="col-6">
        <label class="form-label">Expense Date</label>
        <input type="date" name="expense_date" class="form-control" required>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Expense</button>
        <a href="event_budget_summary.php" class="btn btn-secondary">📊 View Summary</a>
    </div>
</form>

</div>
</body>

</html>