<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Medication</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div class="layout">
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php">🏠 Home</a>
      <a href="add_medication_form.php" class="active">➕ Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php">📅 Add Appointment</a>
      <a href="view_appointments.php">📖 View Appointments</a>
    </nav>

    <main class="content">
      <h1>Add New Medication</h1>

      <?php if (!empty($_SESSION['form_errors'])): ?>
        <div class="error-message">
          <ul>
            <?php foreach ($_SESSION['form_errors'] as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php unset($_SESSION['form_errors']); ?>
      <?php endif; ?>

      <form class="medication-form" action="add_medication.php" method="POST">
        <label for="name">Medication Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="dose">Dosage (e.g., 500mg):</label>
        <input type="text" id="dose" name="dose" required>

        <label for="frequency">Frequency (e.g., 2x/day):</label>
        <input type="text" id="frequency" name="frequency">

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date (optional):</label>
        <input type="date" id="end_date" name="end_date">

        <label for="notes">Notes (optional):</label>
        <textarea id="notes" name="notes"></textarea>

        <button type="submit">Save Medication</button>
      </form>
    </main>
  </div>
</body>
</html>
