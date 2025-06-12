<?php
// confirm_add.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirmation</title>
</head>
<body>

  <h2>Medication Entry</h2>

  <?php if (!empty($_SESSION['success'])): ?>
    <p style="color: green;"><?php echo $_SESSION['success']; ?></p>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <a href="add_medication.php">Add Another Medication</a>

</body>
</html>
