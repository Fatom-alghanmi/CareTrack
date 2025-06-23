<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'database.php'; // $db is PDO instance

// Input sanitization and validation (you should improve this in practice)
$name = $_POST['name'];
$dosage = $_POST['dosage'];
$frequency = $_POST['frequency'];
$start_date = $_POST['start_date'];
$end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
$notes = !empty($_POST['notes']) ? $_POST['notes'] : null;
$reminder_time = !empty($_POST['reminder_time']) ? $_POST['reminder_time'] : null;

// Prepare statement with named placeholders
$sql = "INSERT INTO medications (name, dosage, frequency, start_date, end_date, reminder_time, notes)
        VALUES (:name, :dosage, :frequency, :start_date, :end_date, :reminder_time, :notes)";
$stmt = $db->prepare($sql);

// Bind values (PDO automatically handles null correctly)
$stmt->bindValue(':name', $name);
$stmt->bindValue(':dosage', $dosage);
$stmt->bindValue(':frequency', $frequency);
$stmt->bindValue(':start_date', $start_date);
$stmt->bindValue(':end_date', $end_date);
$stmt->bindValue(':reminder_time', $reminder_time);
$stmt->bindValue(':notes', $notes);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Medication added successfully!";
    header("Location: view_medications.php");
    exit;
} else {
    $_SESSION['error_message'] = "Error adding medication.";
    header("Location: add_medication.php");
    exit;
}
