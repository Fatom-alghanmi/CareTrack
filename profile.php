<?php
session_start();
require_once('database.php');

if (!isset($_SESSION['userName'])) {
    header('Location: login.php');
    exit();
}

$userName = $_SESSION['userName'];

// Fetch current profile image filename
$query = 'SELECT profile_image FROM registrations WHERE userName = :userName';
$stmt = $db->prepare($query);
$stmt->bindValue(':userName', $userName);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$profile_image = ($user && !empty($user['profile_image'])) 
    ? 'images/' . htmlspecialchars($user['profile_image']) 
    : 'images/default.png';

// Get and clear flash messages
$successMessage = $_SESSION['upload_success'] ?? null;
$errorMessage = $_SESSION['upload_error'] ?? null;
unset($_SESSION['upload_success'], $_SESSION['upload_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Profile - CareTrack</title>
    <link rel="stylesheet" href="css/main.css" />
    <style>
        .profile-image { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; }
        .message.success { color: green; }
        .message.error { color: red; }
    </style>
</head>
<body>

<header class="main-header">
  <h1>CareTrack</h1>
</header>


  <div class="nav-profile">
    <img src="<?= $profile_image ?>" alt="Profile Image" class="profile-image" />
  </div>
</nav>

<h1>Your Profile</h1>

<?php if ($successMessage): ?>
    <div id="success-msg" class="message success">
        <?= htmlspecialchars($successMessage) ?>
    </div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div id="error-msg" class="message error">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
<?php endif; ?>

<form action="update_profile_image.php" method="post" enctype="multipart/form-data">
    <label for="new_profile_image">Select a new profile image:</label><br>
    <input type="file" name="new_profile_image" id="new_profile_image" accept="image/*" required><br><br>
    <input type="submit" value="Upload">
    <button type="button" id="cancel-btn">Cancel</button>
</form>

<script>
  // Hide success message after 3 seconds
  window.addEventListener('DOMContentLoaded', () => {
    const successMsg = document.getElementById('success-msg');
    if (successMsg) {
      setTimeout(() => {
        successMsg.style.display = 'none';
      }, 3000);
    }
  });

  // Cancel button redirects back to home/index
  document.getElementById('cancel-btn').addEventListener('click', () => {
    window.location.href = 'index.php';
  });
</script>

</body>
</html>
