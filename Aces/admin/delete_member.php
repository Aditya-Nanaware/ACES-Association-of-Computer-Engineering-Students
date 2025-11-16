<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Optional: delete associated photo file here

    $stmt = $conn->prepare("DELETE FROM committee_members WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_members.php?msg=deleted");
    } else {
        echo "Failed to delete member.";
    }
} else {
    header("Location: manage_members.php");
}
