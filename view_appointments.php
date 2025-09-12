<?php
session_start();
require_once 'database.php';

// Check user is logged in
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

// Fetch all appointments ordered by date ascending
$sql = "SELECT * FROM appointments ORDER BY appointment_date ASC";
$stmt = $db->prepare($sql);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Appointments - CareTrack</title>
  <link rel="stylesheet" href="css/main.css">
  <style>
    .appointments-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .appointments-table th, .appointments-table td {
      padding: 10px 12px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 0.95rem;
      transition: background-color 0.3s, color 0.3s;
    }

    .appointments-table th {
      background-color: #22384d;
      color: #fff;
    }

    .appointments-table tr:nth-child(even) {
      background-color: rgba(255,255,255,0.9);
    }

    body[data-theme="dark"] .appointments-table tr:nth-child(even) {
      background-color: rgba(30,30,30,0.85);
    }

    .actions a {
      margin-right: 5px;
      text-decoration: none;
      color: #0077cc;
    }

    .actions a:hover {
      color: #005fa3;
    }
  </style>
</head>
<body data-theme="light">

<?php include 'header.php'; ?>
<div class="layout">
<?php include 'sidebar.php'; ?>

<main class="content">
  <h2>All Appointments</h2>

  <?php if (!empty($_SESSION['success_message'])): ?>
    <div class="success-message"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <?php if (!empty($appointments)): ?>
    <table class="appointments-table">
      <thead>
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
        <?php foreach ($appointments as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td class="actions">
              <a href="update_appointment.php?id=<?= urlencode($row['id']) ?>">Edit</a>
              <a href="delete_appointment.php?id=<?= urlencode($row['id']) ?>" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No appointments found.</p>
  <?php endif; ?>
</main>
</div>

<?php include 'footer.php'; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const successMsg = document.querySelector('.success-message');
    if (successMsg) {
      setTimeout(() => {
        successMsg.style.transition = 'opacity 0.5s ease';
        successMsg.style.opacity = '0';
        setTimeout(() => successMsg.remove(), 500);
      }, 3000);
    }
  });
</script>
</body>
</html>
