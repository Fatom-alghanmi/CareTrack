<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_FILES['new_profile_image']) || $_FILES['new_profile_image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['upload_error'] = "Please select a valid image file.";
    header('Location: profile.php');
    exit();
}

$userName = $_SESSION['userName'];
$uploadDir = __DIR__ . '/images/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

// Validate file type
$fileType = mime_content_type($_FILES['new_profile_image']['tmp_name']);
if (!in_array($fileType, $allowedTypes)) {
    $_SESSION['upload_error'] = "Only JPG, PNG, and GIF images are allowed.";
    header('Location: profile.php');
    exit();
}

// Generate a unique filename
$ext = pathinfo($_FILES['new_profile_image']['name'], PATHINFO_EXTENSION);
$filename = uniqid('profile_', true) . '.' . $ext;
$destination = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($_FILES['new_profile_image']['tmp_name'], $destination)) {
    $_SESSION['upload_error'] = "Failed to upload image.";
    header('Location: profile.php');
    exit();
}

// Update DB with new filename
$sql = "UPDATE registrations SET profile_image = :profile_image WHERE userName = :userName";
$stmt = $db->prepare($sql);
$stmt->bindValue(':profile_image', $filename);
$stmt->bindValue(':userName', $userName);

if ($stmt->execute()) {
    $_SESSION['upload_success'] = "Profile image updated successfully!";
} else {
    $_SESSION['upload_error'] = "Database update failed.";
}

header('Location: profile.php');
exit();
