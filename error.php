

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$error = $_SESSION['add_error'] ?? 'Unknown error occurred.';
unset($_SESSION['add_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CareTrack - Error</title>
</head>
<body>
    <h2 style="color: red;">Registration Error</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <p><a href="register_user_form.php">← Try registering again</a></p>
</body>
</html>
