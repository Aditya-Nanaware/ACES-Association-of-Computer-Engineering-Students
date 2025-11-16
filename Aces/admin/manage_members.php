<?php
session_start();
include "../includes/db.php";
include "header.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = $conn->query("SELECT * FROM committee_members ORDER BY name ASC");
?>

<h2 class="mb-4">🧑‍💼 Manage Committee Members</h2>

<a href="add_member.php" class="btn btn-success mb-3">➕ Add New Member</a>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Role</th>
            <th>Year</th>
            <th>Branch</th>
            <th>Skills</th>
            <th>Academic Year</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <img src="../uploads/<?php echo $row['photo'] ?: 'default_avatar.png'; ?>" width="50" height="50"
                        class="rounded-circle" style="object-fit: cover;">
                </td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td><?php echo $row['year']; ?></td>
                <td><?php echo htmlspecialchars($row['branch']); ?></td>
                <td><?php echo htmlspecialchars($row['skills']); ?></td>
                <td><?php echo htmlspecialchars($row['academic_year']); ?></td>
                <td>
                    <a href="edit_member.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                    <a href="delete_member.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this member?');">🗑️ Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</div>
</body>

</html>