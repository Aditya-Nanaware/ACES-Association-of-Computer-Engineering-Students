<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$selected_event = $_GET['event_id'] ?? '';
$budget_data = null;
$expenses = [];
$total_spent = 0;
$expenses_list = []; // ✅ Fix: Initialize empty array

// Fetch event list
$events = $conn->query("SELECT id, title FROM events ORDER BY event_date DESC");

if ($selected_event) {
    // Get budget
    $stmt = $conn->prepare("SELECT b.budget_amount, e.title FROM event_budgets b JOIN events e ON b.event_id = e.id WHERE b.event_id = ?");
    $stmt->bind_param("i", $selected_event);
    $stmt->execute();
    $budget_data = $stmt->get_result()->fetch_assoc();

    // Get expenses
    $stmt = $conn->prepare("SELECT description, amount, expense_date FROM event_expenses WHERE event_id = ? ORDER BY expense_date ASC");
    $stmt->bind_param("i", $selected_event);
    $stmt->execute();
    $expenses = $stmt->get_result();

    // Calculate total spent and prepare list
    while ($row = $expenses->fetch_assoc()) {
        $total_spent += $row['amount'];
        $expenses_list[] = $row;
    }

    $remaining = $budget_data['budget_amount'] - $total_spent;
}
?>

<style>
    @media print {

        .navbar,
        form,
        .btn,
        .back-link {
            display: none !important;
        }

        .card {
            page-break-inside: avoid;
        }
    }
</style>

<h2 class="mb-4">📊 Budget Summary</h2>

<form method="GET" class="row mb-4" style="max-width: 500px;">
    <div class="col">
        <select name="event_id" class="form-select" onchange="this.form.submit()" required>
            <option value="">-- Select Event --</option>
            <?php while ($e = $events->fetch_assoc()): ?>
                <option value="<?php echo $e['id']; ?>" <?php if ($selected_event == $e['id'])
                       echo 'selected'; ?>>
                    <?php echo $e['title']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</form>

<?php if ($selected_event && $budget_data): ?>
    <div class="card shadow mb-4">
        <div class="card-body">
            <h4><?php echo $budget_data['title']; ?></h4>
            <p><strong>Total Budget:</strong> ₹<?php echo number_format($budget_data['budget_amount'], 2); ?></p>
            <p><strong>Total Expenses:</strong> ₹<?php echo number_format($total_spent, 2); ?></p>
            <p><strong>Remaining Amount:</strong> ₹<?php echo number_format($remaining, 2); ?></p>
        </div>
    </div>

    <h5>🧾 Expense Details</h5>
    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses_list as $exp): ?>
                <tr>
                    <td><?php echo $exp['expense_date']; ?></td>
                    <td><?php echo $exp['description']; ?></td>
                    <td><?php echo number_format($exp['amount'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-end">
        <button onclick="window.print()" class="btn btn-outline-secondary">🖨️ Print Report</button>
    </div>
<?php elseif ($selected_event): ?>
    <div class="alert alert-warning">No budget set for the selected event.</div>
<?php endif; ?>

</div>
</body>

</html>