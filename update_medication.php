<?php
session_start();
require_once 'database.php';

// Check if ID is provided via GET or POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        // No ID, redirect to view page
        header('Location: view_medications.php');
        exit;
    }

    $id = intval($_GET['id']);

    // Fetch medication data
    $stmt = $conn->prepare("SELECT * FROM medications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        // No medication found with this ID
        $_SESSION['error'] = "Medication not found.";
        header('Location: view_medications.php');
        exit;
    }

    $med = $result->fetch_assoc();

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission

    // Collect POST data and sanitize
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $dosage = trim($_POST['dosage']);
    $frequency = trim($_POST['frequency']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $notes = trim($_POST['notes']);

    // Basic validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($dosage)) {
        $errors[] = "Dosage is required.";
    }
    if (empty($frequency)) {
        $errors[] = "Frequency is required.";
    }
    if (empty($start_date)) {
        $errors[] = "Start date is required.";
    }
    // You can add more validations as needed

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: update_medication.php?id=$id");
        exit;
    }

    // Update medication
    $stmt = $conn->prepare("UPDATE medications SET name=?, dosage=?, frequency=?, start_date=?, end_date=?, notes=? WHERE id=?");
    $stmt->bind_param("ssssssi", $name, $dosage, $frequency, $start_date, $end_date, $notes, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Medication updated successfully.";
        header("Location: view_medications.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating medication: " . $conn->error;
        header("Location: update_medication.php?id=$id");
        exit;
    }
} else {
    // Invalid request method
    header('Location: view_medications.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Medication - CareTrack</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<div class="layout">
  <nav class="sidebar">
    <h2>CareTrack</h2>
    <a href="index.php">🏠 Home</a>
    <a href="add_medication.php">➕ Add Medication</a>
    <a href="view_medications.php">📋 View Medications</a>
    <a href="appointments.php">📅 Appointments</a>
  </nav>

  <main class="content">
    <h1>Update Medication</h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="update_medication.php" method="post" class="medication-form">
      <input type="hidden" name="id" value="<?= htmlspecialchars($med['id']) ?>" />

      <label for="name">Medication Name</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($med['name']) ?>" required />

      <label for="dosage">Dosage</label>
      <input type="text" id="dosage" name="dosage" value="<?= htmlspecialchars($med['dosage']) ?>" required />

      <label for="frequency">Frequency</label>
      <input type="text" id="frequency" name="frequency" value="<?= htmlspecialchars($med['frequency']) ?>" required />

      <label for="start_date">Start Date</label>
      <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($med['start_date']) ?>" required />

      <label for="end_date">End Date (optional)</label>
      <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($med['end_date']) ?>" />

      <label for="notes">Notes (optional)</label>
      <textarea id="notes" name="notes"><?= htmlspecialchars($med['notes']) ?></textarea>

      <button type="submit">Update Medication</button>
    </form>
  </main>
</div>

</body>
</html>
