<?php
session_start();
$message = $_SESSION['message'] ?? 'Action completed.';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2>CareTrack</h2>
            <a href="index.php">Dashboard</a>
            <a href="add_medication_form.php">Add Medication</a>
        </aside>
        <main class="content">
            <h1>Success</h1>
            <p><?= htmlspecialchars($message) ?></p>
            <a href="index.php">Return to Dashboard</a>
        </main>
    </div>
</body>
</html>
