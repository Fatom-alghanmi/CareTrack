<?php
session_start();
require_once 'database.php'; // This defines $db as a PDO instance

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $stmt = $db->prepare("DELETE FROM medications WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Medication deleted successfully.";
        } else {
            $_SESSION['error'] = "Error deleting medication. Please try again.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid medication ID.";
}

header("Location: view_medications.php");
exit;
?>
