<?php
require_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: view_appointments.php');
        exit;
    }

    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        $_SESSION['error'] = "Appointment not found.";
        header('Location: view_appointments.php');
        exit;
    }

    $appointment = $result->fetch_assoc();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $notes = trim($_POST['notes']);

    $errors = [];
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($date)) $errors[] = "Date is required.";
    if (empty($time)) $errors[] = "Time is required.";

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: update_appointment.php?id=$id");
        exit;
    }

    $stmt = $conn->prepare("UPDATE appointments SET title=?, date=?, time=?, notes=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $date, $time, $notes, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment updated successfully.";
        header("Location: view_appointments.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating appointment: " . $conn->error;
        header("Location: update_appointment.php?id=$id");
        exit;
    }
} else {
    header('Location: view_appointments.php');
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Appointment - CareTrack</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>

<div class="layout">
  <nav class="sidebar">
    <h2>CareTrack</h2>
    <a href="index.php">🏠 Home</a>
    <a href="add_medication.php">💊 Add Medication</a>
    <a href="view_medications.php">📋 View Medications</a>
    <a href="appointments.php">📅 Appointments</a>
  </nav>

  <main class="content">
    <h1>Update Appointment</h1>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="error-message"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="update_appointment.php" method="post" class="medication-form">
      <input type="hidden" name="id" value="<?= htmlspecialchars($appointment['id']) ?>" />

      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="<?= htmlspecialchars($appointment['title']) ?>" required />

      <label for="date">Date</label>
      <input type="date" id="date" name="date" value="<?= htmlspecialchars($appointment['date']) ?>" required />

      <label for="time">Time</label>
      <input type="time" id="time" name="time" value="<?= htmlspecialchars($appointment['time']) ?>" required />

      <label for="notes">Notes (optional)</label>
      <textarea id="notes" name="notes"><?= htmlspecialchars($appointment['notes']) ?></textarea>

      <button type="submit">Update Appointment</button>
    </form>
  </main>
</div>

</body>
</html>
