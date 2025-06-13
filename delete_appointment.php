<?php
require_once 'database.php';
session_start();

if (!isset($_GET['id'])) {
    header('Location: view_appointments.php');
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Appointment deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting appointment: " . $conn->error;
}

$conn->close();
header("Location: view_appointments.php");
exit;
?>
