<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all events with a set budget
$events = $conn->query("
    SELECT e.id, e.title, b.budget_amount
    FROM event_budgets b
    JOIN events e ON b.event_id = e.id
    ORDER BY e.event_date DESC
");

$total_budget = 0;
$total_spent_all = 0;
$total_remaining = 0;
?>

<style>
    @media print {

        .navbar,
        .btn,
        .print-btn {
            display: none !important;
        }

        table {
            page-break-inside: avoid;
        }

        body {
            margin: 20px;
        }
    }
</style>

<h2 class="mb-4">🏦 Committee Budget Overview</h2>

<div class="text-end mb-3 print-btn">
    <button onclick="window.print()" class="btn btn-outline-secondary">
        🖨️ Print Full Report
    </button>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Event</th>
            <th>Total Budget (₹)</th>
            <th>Total Spent (₹)</th>
            <th>Remaining (₹)</th>
            <th class="print-btn">📊 Details</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($event = $events->fetch_assoc()): ?>
            <?php
            // Calculate total spent
            $event_id = $event['id'];
            $stmt = $conn->prepare("SELECT SUM(amount) AS total_spent FROM event_expenses WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $spent_result = $stmt->get_result()->fetch_assoc();
            $total_spent = $spent_result['total_spent'] ?? 0;
            $remaining = $event['budget_amount'] - $total_spent;

            // Grand totals
            $total_budget += $event['budget_amount'];
            $total_spent_all += $total_spent;
            $total_remaining += $remaining;
            ?>
            <tr>
                <td><?php echo $event['title']; ?></td>
                <td>₹<?php echo number_format($event['budget_amount'], 2); ?></td>
                <td>₹<?php echo number_format($total_spent, 2); ?></td>
                <td>₹<?php echo number_format($remaining, 2); ?></td>
                <td class="print-btn">
                    <a href="event_budget_summary.php?event_id=<?php echo $event_id; ?>"
                        class="btn btn-sm btn-outline-primary">
                        View Report
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot class="table-light fw-bold">
        <tr>
            <td>Total</td>
            <td>₹<?php echo number_format($total_budget, 2); ?></td>
            <td>₹<?php echo number_format($total_spent_all, 2); ?></td>
            <td>₹<?php echo number_format($total_remaining, 2); ?></td>
            <td class="print-btn"></td>
        </tr>
    </tfoot>
</table>

</div>
</body>

</html>