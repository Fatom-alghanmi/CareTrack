<?php
session_start();
require_once 'database.php';


$today = date('Y-m-d');

// Fetch upcoming medications
$stmtMed = $conn->prepare("SELECT * FROM medications WHERE start_date <= ? AND (end_date IS NULL OR end_date >= ?) ORDER BY start_date ASC");
$stmtMed->bind_param("ss", $today, $today);
$stmtMed->execute();
$resultMed = $stmtMed->get_result();

// Fetch upcoming appointments (assuming you have date column 'appointment_date')
$stmtApp = $conn->prepare("SELECT * FROM appointments WHERE appointment_date >= ? ORDER BY appointment_date ASC");
$stmtApp->bind_param("s", $today);
$stmtApp->execute();
$resultApp = $stmtApp->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CareTrack | Home</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'header.php'; ?>
  <div class="layout">
    <nav class="sidebar">
      <h2>Dashboard Menu</h2>
      <a href="index.php" class="active">🏠 Home</a>
      <a href="add_medication.php">➕ Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php">📅 Add Appointment</a>
      <a href="view_appointments.php">📖 View Appointments</a>
    </nav>

    <main class="content">
      <h2>Welcome to CareTrack</h2>
      <p class="intro">Your personal medication and health manager.</p>

      <h2>Upcoming Medication Reminders</h2>
      <?php if ($resultMed->num_rows > 0): ?>
        <ul>
          <?php while($row = $resultMed->fetch_assoc()): ?>
            <li>
              <?= htmlspecialchars($row['name']) ?> - <?= htmlspecialchars($row['start_date']) ?>
              <?php if (!empty($row['reminder_time'])): ?>
                (Reminder at <?= htmlspecialchars($row['reminder_time']) ?>)
              <?php endif; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>No upcoming medication.</p>
      <?php endif; ?>

      <h2>Upcoming Appointments Reminders</h2>
      <?php if ($resultApp->num_rows > 0): ?>
        <ul>
          <?php while($row = $resultApp->fetch_assoc()): ?>
            <li>
              <?= htmlspecialchars($row['title'] ?? 'Appointment') ?> - <?= htmlspecialchars($row['appointment_date']) ?>
              <?php if (!empty($row['reminder_time'])): ?>
                (Reminder at <?= htmlspecialchars($row['reminder_time']) ?>)
              <?php endif; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>No upcoming appointments.</p>
      <?php endif; ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <p class="message success"><?php echo $_SESSION['success']; ?></p>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
        <p class="message error"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
    </main>
  </div>
  <?php include 'footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>

