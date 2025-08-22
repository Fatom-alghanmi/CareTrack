<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

// Calculate current week start & end
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));

// Fetch medication status counts
$sqlMeds = "SELECT status, COUNT(*) as total
            FROM medications
            WHERE status_date BETWEEN :weekStart AND :weekEnd
            GROUP BY status";

$stmt = $db->prepare($sqlMeds);
$stmt->bindValue(':weekStart', $weekStart);
$stmt->bindValue(':weekEnd', $weekEnd);
$stmt->execute();
$statusCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare default counts
$counts = ['taken' => 0, 'missed' => 0, 'pending' => 0];
foreach ($statusCounts as $row) {
    $counts[$row['status']] = $row['total'];
}

// Fetch appointments scheduled this week
$sqlApps = "SELECT COUNT(*) FROM appointments
            WHERE DATE(appointment_date) BETWEEN :weekStart AND :weekEnd";

$stmt = $db->prepare($sqlApps);
$stmt->bindValue(':weekStart', $weekStart);
$stmt->bindValue(':weekEnd', $weekEnd);
$stmt->execute();
$totalAppointments = $stmt->fetchColumn();

// Calculate compliance rate (%)
$totalTakenOrMissed = $counts['taken'] + $counts['missed'];
$complianceRate = $totalTakenOrMissed > 0 
    ? round(($counts['taken'] / $totalTakenOrMissed) * 100, 1) 
    : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Weekly Summary - CareTrack</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>

<?php include 'header.php'; ?>

<div class="layout">

<?php include 'sidebar.php'; ?>

<main class="content">
  <h2>This Week's Summary (<?= $weekStart ?> to <?= $weekEnd ?>)</h2>

  <div class="summary-cards">
    <div class="summary-card">
      <h3>Medications Taken</h3>
      <p><?= $counts['taken'] ?></p>
    </div>

    <div class="summary-card">
      <h3>Medications Missed</h3>
      <p><?= $counts['missed'] ?></p>
    </div>

    <div class="summary-card">
      <h3>Pending Medications</h3>
      <p><?= $counts['pending'] ?></p>
    </div>

    <div class="summary-card">
      <h3>Appointments This Week</h3>
      <p><?= $totalAppointments ?></p>
    </div>

    <div class="summary-card">
      <h3>Compliance Rate</h3>
      <p><?= $complianceRate ?>%</p>
    </div>
  </div>
</main>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
