<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Appointment</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>

  <div class="layout">
<?php include 'sidebar.php'; ?>
    <main class="content">
      <h2>Add New Appointment</h2>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="save_appointment.php" method="post" class="medication-form">
        <label>Patient Name</label>
        <input type="text" name="patient_name" required>

        <label>Doctor Name</label>
        <input type="text" name="doctor_name" required>

        <label for="appointment_date">Date:</label>
        <input type="date" name="appointment_date" required>
              
        <label for="appointment_time">Time:</label>
        <input type="time" name="appointment_time" required>
 

        <label>Location</label>
        <input type="text" name="location">

        <label>Notes</label>
        <textarea name="notes"></textarea>

        <button type="submit">Save Appointment</button>
      </form>
    </main>
  </div>
<?php include 'footer.php'; ?>
</body>
</html>
