<?php
session_start();
include "../includes/db.php";
include "header.php";

require '../vendor/autoload.php'; // Load PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['excel_file']['tmp_name'];

    if ($file) {
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $inserted = 0;

            foreach ($rows as $i => $row) {
                if ($i === 0)
                    continue; // Skip header row

                [$title, $description, $event_date, $location] = $row;

                if ($title && $event_date) {
                    $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $title, $description, $event_date, $location);
                    if ($stmt->execute())
                        $inserted++;
                }
            }

            $success = "$inserted event(s) imported successfully!";
        } catch (Exception $e) {
            $error = "Failed to import: " . $e->getMessage();
        }
    } else {
        $error = "Please upload a valid Excel (.xlsx) file.";
    }
}
?>

<h2 class="mb-4">📥 Bulk Import Events (Excel)</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
        <label class="form-label">Upload Excel File (.xlsx)</label>
        <input type="file" name="excel_file" class="form-control" accept=".xlsx" required>
    </div>
    <button type="submit" class="btn btn-success">Upload & Import</button>
</form>

<a href="manage_events.php" class="btn btn-secondary">← Back to Event List</a>

</div>
</body>

</html>