<?php
session_start();
require_once 'database.php';

// Redirect if user is not logged in
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

// Get current user info
$userName = $_SESSION['userName'] ?? '';

$stmtUser = $db->prepare("SELECT profile_image FROM registrations WHERE userName = :userName LIMIT 1");
$stmtUser->bindValue(':userName', $userName);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$profileImage = $userData && !empty($userData['profile_image']) ? $userData['profile_image'] : 'default_profile.png';

$today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Appointment | CareTrack</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="layout">
<?php include 'sidebar.php'; ?>
 
<main class="content">
  <h2>Welcome to CareTrack</h2>
  <p>Here is a summary of your medications and appointments.</p>

  <!-- Example: Summary cards -->
  <div class="summary-cards">
    <div class="summary-card">Medications Today: 3</div>
    <div class="summary-card">Upcoming Appointments: 2</div>
    <div class="summary-card">Missed Medications: 0</div>
  </div>
</main>


<?php include 'footer.php'; ?>
</body>
</html>
