<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $skills = $_POST['skills'];
    $academic_year = $_POST['academic_year'];
    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $target = "../uploads/" . $photo_name;

        $ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photo = $photo_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO committee_members (name, role, year, branch, skills, photo, academic_year) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $role, $year, $branch, $skills, $photo, $academic_year);

    if ($stmt->execute()) {
        $success = "✅ Member added successfully!";
    } else {
        $error = "❌ Failed to add member.";
    }
}
?>

<h2 class="mb-4">👥 Add Committee Member</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Role</label>
        <input type="text" name="role" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Year</label>
        <select name="year" class="form-select" required>
            <option value="">Select</option>
            <option value="FE">FE</option>
            <option value="SE">SE</option>
            <option value="TE">TE</option>
            <option value="BE">BE</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Branch</label>
        <input type="text" name="branch" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Academic Year</label>
        <input type="text" name="academic_year" class="form-control" placeholder="e.g. 2024-25" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>
    <div class="col-12">
        <label class="form-label">Skills</label>
        <textarea name="skills" class="form-control" rows="3" placeholder="Leadership, Coding, Design..."></textarea>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Add Member</button>
        <a href="manage_members.php" class="btn btn-secondary">View All Members</a>
    </div>
</form>

</div> <!-- close container -->
</body>

</html>