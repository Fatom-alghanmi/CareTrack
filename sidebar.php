<?php
// sidebar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'database.php';

// Get current page file name
$currentPage = basename($_SERVER['PHP_SELF']);

// Get username from session
$userName = $_SESSION['userName'] ?? '';

// Fetch profile image
$profileImage = 'default_profile.png';
if (!empty($userName)) {
    $stmtUser = $db->prepare("SELECT profile_image FROM registrations WHERE userName = :userName LIMIT 1");
    $stmtUser->bindValue(':userName', $userName);
    $stmtUser->execute();
    $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($userData && !empty($userData['profile_image'])) {
        $profileImage = $userData['profile_image'];
    }
}
?>

<nav class="sidebar">
  <h2>CareTrack</h2>

  <!-- Mode Toggle -->
  <label class="mode-toggle">
    <input type="checkbox" id="modeSwitch">
    <span id="modeIcon">☀️</span>
  </label>

  <div class="profile-image-wrapper">
    <img src="images/<?= htmlspecialchars($profileImage) ?>" alt="Profile Image" class="profile-image" />
  </div>
  <p class="nav-welcome">Welcome, <?= htmlspecialchars($userName); ?></p>

  <a href="index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">🏠 Home</a>
  <a href="add_medication.php" class="<?= $currentPage == 'add_medication.php' ? 'active' : '' ?>">💊 Add Medication</a>
  <a href="view_medications.php" class="<?= $currentPage == 'view_medications.php' ? 'active' : '' ?>">📋 View Medications</a>
  <a href="add_appointment.php" class="<?= $currentPage == 'add_appointment.php' ? 'active' : '' ?>">📅 Add Appointment</a>
  <a href="view_appointments.php" class="<?= $currentPage == 'view_appointments.php' ? 'active' : '' ?>">📖 View Appointments</a>
  <a href="weekly_summary.php" class="<?= $currentPage == 'weekly_summary.php' ? 'active' : '' ?>">📊 Weekly Summary</a>
  <a href="profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
  <a href="logout.php" class="back-link">🚪 Logout</a>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const modeSwitch = document.getElementById("modeSwitch");
  const modeIcon = document.getElementById("modeIcon");
  const body = document.body;

  // Load saved mode
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    body.setAttribute("data-theme", "dark");
    modeSwitch.checked = true;
    modeIcon.textContent = "🌙";
  } else {
    body.setAttribute("data-theme", "light");
    modeSwitch.checked = false;
    modeIcon.textContent = "☀️";
  }

  // Toggle theme
  modeSwitch.addEventListener("change", function () {
    if (modeSwitch.checked) {
      body.setAttribute("data-theme", "dark");
      localStorage.setItem("theme", "dark");
      modeIcon.textContent = "🌙";
    } else {
      body.setAttribute("data-theme", "light");
      localStorage.setItem("theme", "light");
      modeIcon.textContent = "☀️";
    }
  });
});
</script>
