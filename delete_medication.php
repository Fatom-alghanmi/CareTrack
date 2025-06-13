<?php

require_once 'database.php';  // Your DB connection

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM medications WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Medication deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting medication. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid medication ID.";
}

$conn->close();

// Redirect back to the medications list
header("Location: view_medications.php");
exit();
?>
