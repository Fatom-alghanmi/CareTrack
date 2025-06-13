<?php
session_start();
require_once 'database.php';

$patient_name = trim($_POST['patient_name']);
$doctor_name = trim($_POST['doctor_name']);
$appointment_date = $_POST['appointment_date'];
$location = trim($_POST['location']);
$notes = trim($_POST['notes']);

if (!$patient_name || !$doctor_name || !$appointment_date) {
  $_SESSION['error'] = "Please fill all required fields.";
  header("Location: add_appointment.php");
  exit;
}

$sql = "INSERT INTO appointments (patient_name, doctor_name, appointment_date, location, notes)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $patient_name, $doctor_name, $appointment_date, $location, $notes);

if ($stmt->execute()) {
  $_SESSION['success_message'] = "Appointment added successfully.";
} else {
  $_SESSION['error'] = "Error saving appointment.";
}
$stmt->close();
$conn->close();
header("Location: view_appointments.php");
exit;
