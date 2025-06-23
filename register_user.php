<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('message.php');
require_once('database.php');

// Step 1: Get form data
$user_name = trim(filter_input(INPUT_POST, 'user_name'));
$password = trim(filter_input(INPUT_POST, 'password'));
$email_address = trim(filter_input(INPUT_POST, 'email_address'));
$hash = password_hash($password, PASSWORD_DEFAULT);

// Step 2: Handle profile image upload or fallback
$profileImageName = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $profileImageName = uniqid('profile_', true) . '.' . $ext;
    $targetPath = 'uploads/' . $profileImageName;
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath);
} else {
    $profileImageName = 'placeholder.jpg'; // Default image file
}

// Step 3: Check for duplicate username
$queryCheck = 'SELECT * FROM registrations WHERE userName = :userName';
$checkStmt = $db->prepare($queryCheck);
$checkStmt->bindValue(':userName', $user_name);
$checkStmt->execute();
if ($checkStmt->fetch()) {
    $_SESSION["add_error"] = "Duplicate username. Try another.";
    header("Location: error.php");
    exit;
}

// Step 4: Validate inputs
if (!$user_name || !$password || !$email_address) {
    $_SESSION["add_error"] = "Missing required fields.";
    header("Location: error.php");
    exit;
}

// Step 5: Insert user
try {
    $query = 'INSERT INTO registrations (userName, password, emailAddress, profile_image)
              VALUES (:userName, :password, :emailAddress, :profileImage)';
    $stmt = $db->prepare($query);
    $stmt->bindValue(':userName', $user_name);
    $stmt->bindValue(':password', $hash);
    $stmt->bindValue(':emailAddress', $email_address);
    $stmt->bindValue(':profileImage', $profileImageName);
    $stmt->execute();
    $stmt->closeCursor();
} catch (PDOException $e) {
    $_SESSION["add_error"] = "DB error: " . $e->getMessage();
    header("Location: error.php");
    exit;
}

// Step 6: Set session and send email
$_SESSION["isLoggedIn"] = 1;
$_SESSION["userName"] = $user_name;

try {
    send_email(
        $email_address, $user_name,
        'fifi.202408@gmail.com', 'CareTrack',
        'CareTrack - Registration Complete',
        '<p>Thanks for registering with our site.</p><p>Sincerely,<br>CareTrack</p>',
        true
    );
} catch (Exception $ex) {
    $_SESSION["add_error"] = $ex->getMessage();
    header("Location: error.php");
    exit;
}

// Step 7: Redirect to confirmation
header("Location: register_confirmation.php");
exit;
?>
