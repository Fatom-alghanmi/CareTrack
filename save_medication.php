<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'database.php';


// Assume you have input validation here...

$name = $_POST['name'];
$dosage = $_POST['dosage'];
$frequency = $_POST['frequency'];
$start_date = $_POST['start_date'];
$end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
$notes = !empty($_POST['notes']) ? $_POST['notes'] : null;
$reminder_time = $_POST['reminder_time'] ?? null;



$sql = "INSERT INTO medications (name, dosage, frequency, start_date, end_date,reminder_time, notes) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $dosage, $frequency, $start_date, $end_date, $reminder_time, $notes);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Medication added successfully!";
    header("Location: view_medications.php");
    exit;
} else {
    $_SESSION['error_message'] = "Error adding medication: " . $conn->error;
    header("Location: add_medication.php");
    exit;
}
?>
