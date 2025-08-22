<?php
session_start();
require_once 'database.php';

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
  <style>
    .status.taken { color: green; font-weight: bold; }
    .status.missed { color: red; font-weight: bold; }
    .status.pending { color: orange; font-weight: bold; }
    .actions form { display: inline; }
    .actions button { margin: 2px; }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="layout">
  
<?php include 'sidebar.php'; ?>

  <main class="content">
    <h2>All Medications</h2>

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
            <th>Status</th>
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
              <td>
                <span class="status <?= htmlspecialchars($row['status']) ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
              <td class="actions">
                <!-- Mark as Taken -->
                <form method="post" action="update_medication_status.php">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="status" value="taken">
                  <button type="submit">Mark as Taken</button>
                </form>

                <!-- Mark as Missed -->
                <form method="post" action="update_medication_status.php">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="status" value="missed">
                  <button type="submit">Mark as Missed</button>
                </form>

                <!-- Edit/Delete -->
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
</div> <!-- ✅ Only one closing layout div -->

<?php include 'footer.php'; ?>

</body>
</html>
