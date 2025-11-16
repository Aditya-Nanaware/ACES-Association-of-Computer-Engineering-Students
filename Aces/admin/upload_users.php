<?php
session_start();
include "../includes/db.php";
include "header.php";

require '../vendor/autoload.php';
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
                    continue; // skip header

                [$name, $email, $password] = $row;

                if ($name && $email && $password) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $role = 'student';

                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
                    if ($stmt->execute())
                        $inserted++;
                }
            }

            $success = "$inserted student(s) imported successfully!";
        } catch (Exception $e) {
            $error = "Failed to import: " . $e->getMessage();
        }
    } else {
        $error = "Please upload a valid Excel file.";
    }
}
?>

<h2 class="mb-4">📥 Upload Students via Excel</h2>

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

<a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>

</div>
</body>

</html>