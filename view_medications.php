<?php
session_start();
require_once 'database.php';  // Defines $db as PDO

// Fetch all medications
$sql = "SELECT * FROM medications ORDER BY created_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$medications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Medications - CareTrack</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>

<?php include 'header.php'; ?>

<div class="layout">
<nav class="sidebar">
  <h2>CareTrack</h2>
  <a href="index.php">🏠 Home</a>
    <a href="add_medication.php">💊 Add Medication</a>
  <a href="view_medications.php" class="active">📋 View Medications</a>
  <a href="add_appointment.php">📅 Add Appointment</a>
  <a href="view_appointments.php">📖 View Appointments</a>
  <p><a href="logout.php" class="back-link">Logout</a></p>
</nav>

<?php 
if (!empty($_SESSION['success_message'])) {
    echo '<div class="success-message">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    echo '<div class="error-message">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}
?>

<main class="content">
    <h2>All Medications</h2>

    <?php if (!empty($medications)): ?>
      <table class="medications-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Dosage</th>
            <th>Frequency</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Notes</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($medications as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['dosage']) ?></td>
              <td><?= htmlspecialchars($row['frequency']) ?></td>
              <td><?= htmlspecialchars($row['start_date']) ?></td>
              <td><?= htmlspecialchars($row['end_date'] ?: '-') ?></td>
              <td><?= htmlspecialchars($row['notes'] ?: '-') ?></td>
              <td class="actions">
                <a href="update_medication.php?id=<?= urlencode($row['id']) ?>">Edit</a>
                <a href="delete_medication.php?id=<?= urlencode($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this medication?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No medications found. <a href="add_medication.php">Add your first medication</a>.</p>
    <?php endif; ?>
</main>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
