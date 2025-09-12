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
  <style>
    .summary-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .summary-card {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      transition: transform 0.3s, background-color 0.3s;
      text-align: center;
    }

    .summary-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.25);
    }

    .summary-card h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #0077cc;
    }

    .summary-card p {
      font-size: 2rem;
      font-weight: bold;
      color: #22384d;
    }

    /* Color coding for specific cards */
    .med-taken p { color: #28a745; }
    .med-missed p { color: #d93025; }
    .med-pending p { color: #ff9900; }
    .appointments p { color: #4482d8; }
    .compliance p { color: #6f42c1; }

    /* Dark mode */
    body[data-theme="dark"] .summary-card {
      background-color: rgba(30, 30, 30, 0.85);
      color: #e0e0e0;
    }

    body[data-theme="dark"] .summary-card h3 { color: #66b2ff; }
  </style>
</head>
<body data-theme="light">

<?php include 'header.php'; ?>
<div class="layout">
<?php include 'sidebar.php'; ?>

<main class="content">
  <h2 style="text-align:center;">This Week's Summary <br><small><?= $weekStart ?> to <?= $weekEnd ?></small></h2>

  <div class="summary-cards">
  <a href="view_medications.php?status=taken" class="summary-card med-taken">
    <h3>Medications Taken</h3>
    <p><?= $counts['taken'] ?></p>
  </a>

  <a href="view_medications.php?status=missed" class="summary-card med-missed">
    <h3>Medications Missed</h3>
    <p><?= $counts['missed'] ?></p>
  </a>

  <a href="view_medications.php?status=pending" class="summary-card med-pending">
    <h3>Pending Medications</h3>
    <p><?= $counts['pending'] ?></p>
  </a>

  <a href="view_appointments.php?week=current" class="summary-card appointments">
    <h3>Appointments This Week</h3>
    <p><?= $totalAppointments ?></p>
  </a>

  <div class="summary-card compliance">
    <h3>Compliance Rate</h3>
    <p><?= $complianceRate ?>%</p>
  </div>
</div>

</main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
