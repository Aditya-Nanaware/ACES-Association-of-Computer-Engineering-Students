<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];
include "header.php"; // Includes navbar and opens <html> and container
?>

<h2 class="mb-5">Welcome, Admin <?php echo htmlspecialchars($user['name']); ?> 👑</h2>

<div class="list-group">
    <a href="add_event.php" class="list-group-item list-group-item-action">➕ Add Event</a>
    <a href="manage_events.php" class="list-group-item list-group-item-action">🛠️ Manage Events</a>
    <a href="upload_events.php" class="list-group-item list-group-item-action">📥 Upload Events (Excel)</a>
    <a href="upload_users.php" class="list-group-item list-group-item-action">👥 Upload Students (Excel)</a>

    <!-- 👥 Committee Management Section -->
    <a href="add_member.php" class="list-group-item list-group-item-action">➕ Add Committee Member</a>
    <a href="manage_members.php" class="list-group-item list-group-item-action">🛠️ Manage Committee Members</a>
    <a href="export_members.php" class="list-group-item list-group-item-action">📤 Export Committee (CSV / PDF)</a>
    <a href="set_budget.php" class="list-group-item list-group-item-action">💰 Set Event Budget</a>
    <a href="add_expense.php" class="list-group-item list-group-item-action">➕ Add Event Expense</a>
    <a href="event_budget_summary.php" class="list-group-item list-group-item-action">📊 View Budget Summary</a>
    <a href="budget_overview.php" class="list-group-item list-group-item-action">🏦 Committee Balance Overview</a>

    <a href="upload_photos.php" class="list-group-item list-group-item-action">🏦Upload Photos</a>


    <a href="../logout.php" class="list-group-item list-group-item-action text-danger">🚪 Logout</a>
</div>

</div> <!-- close .container from header -->
</body>

</html>