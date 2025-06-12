<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CareTrack | Home</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="layout">
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php">🏠 Home</a>
      <a href="add_medication.php">➕ Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="appointments.php">📅 Appointments</a>
    </nav>

    <main class="content">
      <h1>Welcome to CareTrack</h1>
      <p class="intro">Your personal medication and health manager.</p>

      <?php if (!empty($_SESSION['success'])): ?>
        <p class="message success"><?php echo $_SESSION['success']; ?></p>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
        <p class="message error"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
    </main>
  </div>

</body>
</html>
