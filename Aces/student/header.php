<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>ACES Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">ACES</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/student/events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/student/my_events.php">My Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/logout.php">Logout</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/student/view_committee.php">Committee</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/student/gallery.php">📷 Event Gallery</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/student/profile.php">Profile</a>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <div class="container mt-4">