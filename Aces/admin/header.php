<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>ACES Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/Aces/admin/dashboard.php">ACES Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
                aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/admin/dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/admin/add_event.php">Add Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/admin/manage_events.php">Manage Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/admin/upload_events.php">Upload Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/admin/add_member.php">Add Committee Member</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Aces/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">