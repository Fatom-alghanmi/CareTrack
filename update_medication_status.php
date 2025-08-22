<?php
session_start();
require_once 'database.php';

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$status = filter_input(INPUT_POST, 'status');

if (!$id || !in_array($status, ['taken', 'missed', 'pending'])) {
    $_SESSION['error_message'] = "Invalid status update.";
    header("Location: view_medications.php");
    exit;
}

$query = "UPDATE medications SET status = :status, status_date = CURDATE() WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':id', $id);
$stmt->execute();

$_SESSION['success_message'] = "Medication status updated.";
header("Location: view_medications.php");
exit;
