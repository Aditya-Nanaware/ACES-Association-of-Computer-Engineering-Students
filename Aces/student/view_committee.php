<?php
include "../includes/db.php";
include "header.php";

$selected_year = $_GET['year'] ?? '';
$years_result = $conn->query("SELECT DISTINCT academic_year FROM committee_members ORDER BY academic_year DESC");


$selected_year = $_GET['year'] ?? '';
$years_result = $conn->query("SELECT DISTINCT academic_year FROM committee_members ORDER BY academic_year DESC");

$members = [];

if ($selected_year) {
    $stmt = $conn->prepare("SELECT * FROM committee_members WHERE academic_year = ? ORDER BY name ASC");
    $stmt->bind_param("s", $selected_year);
    $stmt->execute();
    $members = $stmt->get_result();
}
?>

<!-- 👕 Add print styles -->
<style>
    @media print {

        .navbar,
        .btn,
        form {
            display: none !important;
        }

        .card {
            page-break-inside: avoid;
        }
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4 text-center">👥 ACES Committee Members</h2>

    <!-- 🔽 Year dropdown -->
    <form method="GET" class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <select name="year" class="form-select" onchange="this.form.submit()">
                <option value="">-- Select Academic Year --</option>
                <?php while ($row = $years_result->fetch_assoc()): ?>
                    <option value="<?php echo $row['academic_year']; ?>" <?php if ($selected_year == $row['academic_year'])
                           echo 'selected'; ?>>
                        <?php echo $row['academic_year']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>

    <!-- 🖨️ Print button -->
    <?php if ($selected_year && $members->num_rows > 0): ?>
        <div class="text-end mb-3">
            <button onclick="window.print()" class="btn btn-outline-secondary">
                🖨️ Print / Save as PDF
            </button>
        </div>
    <?php endif; ?>

    <!-- 🧑‍💼 Members -->
    <?php if ($selected_year && $members->num_rows > 0): ?>
        <div class="row">
            <?php while ($m = $members->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <img src="uploads/<?php echo $m['photo'] ?: 'default_avatar.png'; ?>" class="card-img-top" height="220"
                            style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $m['name']; ?></h5>
                            <p class="card-text mb-1"><strong>Role:</strong> <?php echo $m['role']; ?></p>
                            <p class="card-text mb-1">
                                <strong>Year:</strong> <?php echo $m['year']; ?> |
                                <strong>Branch:</strong> <?php echo $m['branch']; ?>
                            </p>
                            <p class="card-text"><strong>Skills:</strong> <?php echo $m['skills']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php elseif ($selected_year): ?>
        <div class="alert alert-warning text-center">No members found for "<?php echo $selected_year; ?>"</div>
    <?php endif; ?>
</div>

</body>

</html>