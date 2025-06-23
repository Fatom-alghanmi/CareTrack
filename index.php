<?php
// Your existing session_start, require database, etc.
session_start();
require_once 'database.php';

// Fetch latest appointments (you can limit or sort them if needed)
$query = "SELECT * FROM appointments ORDER BY appointment_date ASC";
$stmt = $db->prepare($query);
$stmt->execute();
$appointments = $stmt->fetchAll();

// Fetch medications
$medStmt = $db->prepare("SELECT * FROM medications ORDER BY start_date DESC");
$medStmt->execute();
$medications = $medStmt->fetchAll();


if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

$userName = $_SESSION['userName'] ?? '';

$stmtUser = $db->prepare("SELECT profile_image FROM registrations WHERE userName = :userName LIMIT 1");
$stmtUser->bindValue(':userName', $userName);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$profileImage = $userData && !empty($userData['profile_image']) ? $userData['profile_image'] : 'default_profile.png';

$today = date('Y-m-d');

// Fetch upcoming medications and appointments as you already do
// ...

// Fetch upcoming medications
$stmtMed = $db->prepare("SELECT * FROM medications WHERE start_date <= :today AND (end_date IS NULL OR end_date >= :today) ORDER BY start_date ASC");
$stmtMed->bindValue(':today', $today);
$stmtMed->execute();
$medications = $stmtMed->fetchAll(PDO::FETCH_ASSOC);

// Fetch upcoming appointments
$stmtApp = $db->prepare("SELECT * FROM appointments WHERE appointment_date >= :today ORDER BY appointment_date ASC");
$stmtApp->bindValue(':today', $today);
$stmtApp->execute();
$appointments = $stmtApp->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>CareTrack | Home</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<img src="images/<?= htmlspecialchars($profile_image) ?>" alt="Profile Image" class="profile-image" />


<?php include 'header.php'; ?>
  <div class="layout">
    <nav class="sidebar">
      <h2>CareTrack</h2>
      <a href="index.php" class="active">🏠 Home</a>
    <a href="add_medication.php">💊 Add Medication</a>
      <a href="view_medications.php">📋 View Medications</a>
      <a href="add_appointment.php">📅 Add Appointment</a>
      <a href="view_appointments.php">📖 View Appointments</a>
      <p><a href="logout.php" class="back-link">Logout</a></p>
    </nav>

    <main class="content">
      <h1>Welcome to CareTrack</h1>
      <p class="intro">Your personal medication and health manager.</p>

      <div class="card-grid">
  <?php foreach ($medications as $med): ?>
    <div class="info-card">
      <h3><?= htmlspecialchars($med['name']) ?></h3>
      <p><strong>Dosage:</strong> <?= $med['dosage'] ?></p>
      <p><strong>Frequency:</strong> <?= $med['frequency'] ?></p>
      <p><strong>Start:</strong> <?= $med['start_date'] ?></p>
      <p><strong>End:</strong> <?= $med['end_date'] ?? 'N/A' ?></p>
      <p><strong>Reminder:</strong> <?= $med['reminder_time'] ?? 'N/A' ?></p>
    </div>
  <?php endforeach; ?>
</div>

      <h2>Upcoming Appointments</h2>

<?php if ($appointments): ?>
  <table class="medications-table">
    <thead>
      <tr>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Date & Time</th>
        <th>Location</th>
        <th>Notes</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($appointments as $appointment): ?>
        <tr>
          <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
          <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
          <td><?= date("Y-m-d H:i", strtotime($appointment['appointment_date'])) ?></td>
          <td><?= htmlspecialchars($appointment['location']) ?></td>
          <td><?= nl2br(htmlspecialchars($appointment['notes'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No appointments scheduled.</p>
<?php endif; ?>


      <?php if (!empty($_SESSION['success'])): ?>
        <p class="message success"><?= htmlspecialchars($_SESSION['success']) ?></p>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (!empty($_SESSION['error'])): ?>
        <p class="message error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>
    </main>
  </div>
<?php include 'footer.php'; ?>
</body>
</html>
