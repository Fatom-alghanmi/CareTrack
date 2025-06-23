<?php
require_once 'database.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_appointments.php');
    exit;
}

$id = intval($_GET['id']);

try {
    $stmt = $db->prepare("DELETE FROM appointments WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Appointment deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete appointment.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
}

header("Location: view_appointments.php");
exit;
?>
