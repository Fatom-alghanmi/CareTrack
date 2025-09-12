<?php
session_start();
require_once 'database.php';

// Redirect if user is not logged in
if (!isset($_SESSION["isLoggedIn"]) || $_SESSION["isLoggedIn"] !== true) {
    header("Location: login_form.php");
    exit;
}
// Get current user info
$userName = $_SESSION['userName'] ?? '';

$stmtUser = $db->prepare("SELECT profile_image FROM registrations WHERE userName = :userName LIMIT 1");
$stmtUser->bindValue(':userName', $userName);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$profileImage = $userData && !empty($userData['profile_image']) ? $userData['profile_image'] : 'default_profile.png';

$today = date('Y-m-d');

// Wellness & motivational tips
$tips = [
    "Take a deep breath and drink a glass of water to refresh your mind.",
    "Consistency is the key — one small step every day makes a big difference.",
    "Don’t forget to stretch! A few minutes can boost your energy.",
    "Stay positive — every medication you take is a step toward better health.",
    "Rest is as important as action. Give yourself time to recharge.",
    "A short walk can do wonders for both your body and mind."
];

// Pick a random tip
$tipOfTheDay = $tips[array_rand($tips)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | CareTrack</title>
  <link rel="stylesheet" href="css/main.css">
</head>
<body data-theme="light">

<?php include 'header.php'; ?>

<div class="layout">
<?php include 'sidebar.php'; ?>
 
<main class="content">
  <h2>Welcome to CareTrack</h2>
  <p>Here is a summary of your medications and appointments.</p>

  <div class="summary-cards home-summary-cards">
    <div class="summary-card">
      <h3>Medications Today</h3>
      <p>3</p>
      <a href="view_medications.php" class="detail-link">Detail</a>
    </div>
    <div class="summary-card">
      <h3>Upcoming Appointments</h3>
      <p>2</p>
      <a href="view_appointments.php" class="detail-link">Detail</a>
    </div>
    <div class="summary-card">
      <h3>Missed Medications</h3>
      <p>0</p>
      <a href="view_medications.php?status=missed" class="detail-link">Detail</a>
    </div>
  </div>

  <!-- Motivational / Wellness Tip -->
  <div class="wellness-tip">
    <h3>💡 Wellness Tip</h3>
    <p><?php echo htmlspecialchars($tipOfTheDay); ?></p>
  </div>

</main>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
