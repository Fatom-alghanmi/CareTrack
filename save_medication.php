<?php
// save_medication.php

// Include database connection
require_once 'database.php';

// Get form data
$name = $_POST['name'] ?? '';
$dosage = $_POST['dosage'] ?? '';
$frequency = $_POST['frequency'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? null; // Optional
$notes = $_POST['notes'] ?? '';

// Validate required fields
if ($name && $dosage && $frequency && $start_date) {
    // Prepare and execute SQL insert
    $sql = "INSERT INTO medications (name, dosage, frequency, start_date, end_date, notes)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $dosage, $frequency, $start_date, $end_date, $notes);

    if ($stmt->execute()) {
        header("Location: confirm_add.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Please fill in all required fields.";
}

$conn->close();
?>
