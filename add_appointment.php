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
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php">🏠 Home</a>
    <a href="add_medication.php">💊 Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php" class="active">📅 Add Appointment</a>
      <a href="view_appointments.php">📖 View Appointments</a>
      <p><a href="logout.php" class="back-link">Logout</a></p>
    </nav>

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

        <label>Appointment Date</label>
        <input type="datetime-local" name="appointment_date" required>

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
