<?php
session_start();
require_once 'database.php';  // provides $db as PDO

// Redirect if not logged in (optional but recommended)
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: view_appointments.php');
        exit;
    }

    $id = intval($_GET['id']);

    $stmt = $db->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->execute([$id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$appointment) {
        $_SESSION['error'] = "Appointment not found.";
        header('Location: view_appointments.php');
        exit;
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $patient_name = trim($_POST['patient_name']);
    $doctor_name = trim($_POST['doctor_name']);
    $appointment_date = trim($_POST['appointment_date']);
    $location = trim($_POST['location']);
    $notes = trim($_POST['notes']);

    $errors = [];
    if (empty($patient_name)) $errors[] = "Patient name is required.";
    if (empty($doctor_name)) $errors[] = "Doctor name is required.";
    if (empty($appointment_date)) $errors[] = "Appointment date is required.";
    if (empty($location)) $errors[] = "Location is required.";

    if (!empty($errors)) {
        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: update_appointment.php?id=$id");
        exit;
    }

    $sql = "UPDATE appointments SET patient_name = :patient_name, doctor_name = :doctor_name, appointment_date = :appointment_date, location = :location, notes = :notes WHERE id = :id";

    $stmt = $db->prepare($sql);
    $params = [
        ':patient_name' => $patient_name,
        ':doctor_name' => $doctor_name,
        ':appointment_date' => $appointment_date,
        ':location' => $location,
        ':notes' => $notes ?: null,
        ':id' => $id,
    ];

    if ($stmt->execute($params)) {
        $_SESSION['success'] = "Appointment updated successfully.";
        header("Location: view_appointments.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating appointment.";
        header("Location: update_appointment.php?id=$id");
        exit;
    }
} else {
    header('Location: view_appointments.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Update Appointment - CareTrack</title>
  <link rel="stylesheet" href="css/main.css" />
</head>
<body>
<?php include 'header.php'; ?>

<div class="layout">
  
<?php include 'sidebar.php'; ?>

  <main class="content">
    <h1>Update Appointment</h1>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form action="update_appointment.php" method="post" class="medication-form">
      <input type="hidden" name="id" value="<?= htmlspecialchars($appointment['id']) ?>" />

      <label for="patient_name">Patient Name</label>
      <input type="text" id="patient_name" name="patient_name" value="<?= htmlspecialchars($appointment['patient_name']) ?>" required />

      <label for="doctor_name">Doctor Name</label>
      <input type="text" id="doctor_name" name="doctor_name" value="<?= htmlspecialchars($appointment['doctor_name']) ?>" required />

      <label for="appointment_date">Appointment Date</label>
      <input type="date" id="appointment_date" name="appointment_date" value="<?= htmlspecialchars($appointment['appointment_date']) ?>" required />

      <label for="location">Location</label>
      <input type="text" id="location" name="location" value="<?= htmlspecialchars($appointment['location']) ?>" required />

      <label for="notes">Notes (optional)</label>
      <textarea id="notes" name="notes"><?= htmlspecialchars($appointment['notes']) ?></textarea>

      <button type="submit">Update Appointment</button>
    </form>
  </main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
