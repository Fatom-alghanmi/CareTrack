<!-- add_medication.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Medication</title>
  <link rel="stylesheet" href="css/main.css"> <!-- Optional CSS -->
</head>
<body>
<div class="layout">
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php">🏠 Home</a>
      <a href="add_medication.php">➕ Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php" class="active">📅 Add Appointment</a>
      <a href="view_appointments.php">📖 View Appointments</a>
    </nav>

    <main class="content">
  <h2>Add Medication</h2>

  <form action="save_medication.php" method="POST">
    <label for="name">Medication Name:</label><br>
    <input type="text" name="name" id="name" required><br><br>

    <label for="dosage">Dosage (e.g., 500mg):</label><br>
    <input type="text" name="dosage" id="dosage" required><br><br>

    <label for="frequency">Frequency (e.g., 2x/day):</label><br>
    <input type="text" name="frequency" id="frequency" required><br><br>

    <label for="start_date">Start Date:</label><br>
    <input type="date" name="start_date" id="start_date" required><br><br>

    <label for="end_date">End Date (optional):</label><br>
    <input type="date" name="end_date" id="end_date"><br><br>

    <label for="notes">Notes (optional):</label><br>
    <textarea name="notes" id="notes" rows="4" cols="40"></textarea><br><br>

    <button type="submit">Save Medication</button>
  </form>

</body>
</html>
