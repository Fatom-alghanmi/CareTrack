<?php
session_start();
require_once 'database.php'; // provides $db as PDO

// Redirect if not logged in (optional but recommended)
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: view_medications.php');
        exit;
    }

    $id = intval($_GET['id']);

    // Fetch medication data with PDO
    $stmt = $db->prepare("SELECT * FROM medications WHERE id = ?");
    $stmt->execute([$id]);
    $med = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$med) {
        $_SESSION['error'] = "Medication not found.";
        header('Location: view_medications.php');
        exit;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $dosage = trim($_POST['dosage']);
    $frequency = trim($_POST['frequency']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $notes = trim($_POST['notes']);

    // Validate inputs
    $errors = [];
    if (empty($name)) $errors[] = "Name is required.";
    if (empty($dosage)) $errors[] = "Dosage is required.";
    if (empty($frequency)) $errors[] = "Frequency is required.";
    if (empty($start_date)) $errors[] = "Start date is required.";

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: update_medication.php?id=$id");
        exit;
    }

    // Update with PDO and named parameters
    $sql = "UPDATE medications SET name = :name, dosage = :dosage, frequency = :frequency, 
            start_date = :start_date, end_date = :end_date, notes = :notes WHERE id = :id";

    $stmt = $db->prepare($sql);
    $params = [
        ':name' => $name,
        ':dosage' => $dosage,
        ':frequency' => $frequency,
        ':start_date' => $start_date,
        ':end_date' => $end_date ?: null,
        ':notes' => $notes ?: null,
        ':id' => $id,
    ];

    if ($stmt->execute($params)) {
        $_SESSION['success'] = "Medication updated successfully.";
        header("Location: view_medications.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating medication.";
        header("Location: update_medication.php?id=$id");
        exit;
    }

} else {
    // Invalid request method
    header('Location: view_medications.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Medication - CareTrack</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<?php include 'header.php'; ?>
<div class="layout">

<div class="layout">
  
<?php include 'sidebar.php'; ?>
  
  <main class="content">
    <h2>Update Medication</h2>

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
  <button type="button" onclick="window.location.href='view_medications.php'">Cancel</button>
</form>

  </main>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
