<?php
session_start();
include "../includes/db.php";
include "header.php";

// Check admin access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Get distinct academic years for dropdown
$years_result = $conn->query("SELECT DISTINCT academic_year FROM committee_members ORDER BY academic_year DESC");
?>

<h2 class="mb-4">📤 Export Committee by Academic Year</h2>

<form method="GET" action="export_members_download.php" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Select Academic Year</label>
        <select name="year" class="form-select" required>
            <option value="">-- Choose Year --</option>
            <?php while ($row = $years_result->fetch_assoc()): ?>
                <option value="<?php echo $row['academic_year']; ?>"><?php echo $row['academic_year']; ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Export Format</label>
        <select name="format" class="form-select" required>
            <option value="csv">CSV</option>
            <option value="pdf">PDF</option>
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-success">📥 Download</button>
        <a href="dashboard.php" class="btn btn-secondary">← Back</a>
    </div>
</form>

</div>
</body>

</html>