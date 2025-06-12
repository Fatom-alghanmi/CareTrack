<?php
session_start();
require_once 'database.php';  // Your DB connection

// Fetch all medications
$sql = "SELECT * FROM medications ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>View Medications - CareTrack</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<div class="layout">
  <nav class="sidebar">
    <h2>CareTrack</h2>
    <a href="index.php">🏠 Home</a>
    <a href="add_medication.php">➕ Add Medication</a>
    <a href="view_medications.php" class="active">📋 View Medications</a>
    <a href="appointments.php">📅 Appointments</a>
  </nav>

  <main class="content">
    <h1>All Medications</h1>

    <?php if ($result->num_rows > 0): ?>
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
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['dosage']) ?></td>
              <td><?= htmlspecialchars($row['frequency']) ?></td>
              <td><?= htmlspecialchars($row['start_date']) ?></td>
              <td><?= htmlspecialchars($row['end_date'] ?: '-') ?></td>
              <td><?= htmlspecialchars($row['notes'] ?: '-') ?></td>
              <td class="actions">
                <a href="update_medication.php?id=<?= $row['id'] ?>">Edit</a>
                <a href="delete_medication.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this medication?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No medications found. <a href="add_medication.php">Add your first medication</a>.</p>
    <?php endif; ?>

  </main>
</div>

</body>
</html>

<?php
$conn->close();
?>
