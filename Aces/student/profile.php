<?php
session_start();
include "../includes/db.php";
include "header.php"; // Includes Bootstrap + navbar

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// 🆕 Refresh user data to avoid undefined profile_photo
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$_SESSION['user'] = $user;

$success = $error = null;

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $password = $_POST['password'];
    $profile_photo = $user['profile_photo']; // fallback

    if (!empty($_FILES['photo']['name'])) {
        $photo_name = time() . "_" . basename($_FILES['photo']['name']);
        $target_path = "../uploads/" . $photo_name;
        $ext = strtolower(pathinfo($photo_name, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png']) && move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            $profile_photo = $photo_name;
        }
    }

    $update_query = "UPDATE users SET name = ?, profile_photo = ?";
    $params = [$name, $profile_photo];
    $types = "ss";

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = ?";
        $params[] = $hashed;
        $types .= "s";
    }

    $update_query .= " WHERE id = ?";
    $params[] = $user_id;
    $types .= "i";

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $success = "✅ Profile updated successfully!";
        // 🆕 Refresh session again after update
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $_SESSION['user'] = $user;
    } else {
        $error = "❌ Failed to update profile.";
    }
}
?>

<!-- 👤 My Profile UI -->
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">👤 My Profile</h3>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="text-center mb-4">
                    <img src="../uploads/<?php echo $user['profile_photo'] ?: 'default_avatar.png'; ?>"
                        class="rounded-circle border shadow-sm" width="120" height="120" style="object-fit: cover;"
                        alt="Profile Photo">
                </div>

                <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required
                            value="<?php echo htmlspecialchars($user['name']); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" readonly
                            value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Leave blank to keep current">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Upload Profile Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">💾 Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div> <!-- close container -->
</body>

</html>