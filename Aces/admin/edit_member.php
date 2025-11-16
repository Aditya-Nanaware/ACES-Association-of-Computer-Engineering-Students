<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: manage_members.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM committee_members WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$member = $stmt->get_result()->fetch_assoc();

if (!$member) {
    die("Member not found.");
}

$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $skills = $_POST['skills'];
    $academic_year = $_POST['academic_year'];
    $photo = $member['photo'];

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $target = "../uploads/" . $photo_name;

        $ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $photo_name;
        }
    }

    $stmt = $conn->prepare("UPDATE committee_members SET name = ?, role = ?, year = ?, branch = ?, skills = ?, photo = ?, academic_year = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $role, $year, $branch, $skills, $photo, $academic_year, $id);

    if ($stmt->execute()) {
        $success = "✅ Member updated successfully!";
        // Refresh data
        $stmt = $conn->prepare("SELECT * FROM committee_members WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $member = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "❌ Failed to update.";
    }
}
?>

<h2 class="mb-4">✏️ Edit Committee Member</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $member['name']; ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Role</label>
        <input type="text" name="role" class="form-control" value="<?php echo $member['role']; ?>" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Year</label>
        <select name="year" class="form-select" required>
            <?php
            $years = ['FE', 'SE', 'TE', 'BE'];
            foreach ($years as $y) {
                echo "<option value='$y' " . ($member['year'] == $y ? 'selected' : '') . ">$y</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Branch</label>
        <input type="text" name="branch" class="form-control" value="<?php echo $member['branch']; ?>" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Academic Year</label>
        <input type="text" name="academic_year" class="form-control" value="<?php echo $member['academic_year']; ?>"
            placeholder="e.g. 2024-25" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Change Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>
    <div class="col-12">
        <label class="form-label">Skills</label>
        <textarea name="skills" class="form-control" rows="3"><?php echo $member['skills']; ?></textarea>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update Member</button>
        <a href="manage_members.php" class="btn btn-secondary">← Back</a>
    </div>
</form>

</div>
</body>

</html>