<?php
session_start();
require_once 'database.php';  // $db is PDO instance

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
        VALUES (:patient_name, :doctor_name, :appointment_date, :location, :notes)";
$stmt = $db->prepare($sql);

$stmt->bindValue(':patient_name', $patient_name);
$stmt->bindValue(':doctor_name', $doctor_name);
$stmt->bindValue(':appointment_date', $appointment_date);
$stmt->bindValue(':location', $location);
$stmt->bindValue(':notes', $notes);

if ($stmt->execute()) {
  $_SESSION['success_message'] = "Appointment added successfully.";
} else {
  $_SESSION['error'] = "Error saving appointment.";
}

header("Location: view_appointments.php");
exit;
