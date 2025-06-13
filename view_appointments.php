<?php
require_once 'database.php';
session_start();

// Fetch appointments
$sql = "SELECT * FROM appointments ORDER BY appointment_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Appointments</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div class="layout">
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php">🏠 Home</a>
      <a href="add_medication.php">➕ Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php">📅 Add Appointment</a>
      <a href="view_appointments.php" class="active">📖 View Appointments</a>
    </nav>

    <main class="content">
      <h1>All Appointments</h1>

      <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

      <?php if ($result && $result->num_rows > 0): ?>
        <div style="overflow-x:auto;">
          <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse: collapse; background:#fff;">
            <thead style="background:#549fd1; color:white;">
              <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Location</th>
                <th>Notes</th>
                <th>Created At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                  <a href="update_appointment.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
                  <a href="delete_appointment.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this appointment?')">❌ Delete</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p>No appointments found.</p>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
