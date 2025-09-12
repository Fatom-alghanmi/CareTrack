<?php
session_start();
require_once 'database.php'; // $db PDO
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userName = 'example_user'; // Replace with dynamic username if needed
$today = date('Y-m-d');
$now = date('H:i:s');

// Helper function to send email
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('your_email@example.com', 'CareTrack');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}

// =====================
// Medications Reminder
// =====================
$sqlMeds = "SELECT id, name, dosage, frequency, reminder_time
            FROM medications
            WHERE userName = :userName
              AND status = 'pending'
              AND (end_date >= :today OR end_date IS NULL)
              AND start_date <= :today
              AND reminder_sent = 0
              AND (reminder_time <= :now OR reminder_time IS NULL)";
$stmt = $db->prepare($sqlMeds);
$stmt->execute([
    ':userName' => $userName,
    ':today' => $today,
    ':now' => $now
]);
$medications = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($medications as $med) {
    $subject = "Medication Reminder: {$med['name']}";
    $body = "Hi {$userName},<br><br>
             This is a reminder to take your medication:<br>
             Name: {$med['name']}<br>
             Dosage: {$med['dosage']}<br>
             Frequency: {$med['frequency']}<br><br>
             - CareTrack";

    if (sendEmail('user_email@example.com', $subject, $body)) {
        $update = $db->prepare("UPDATE medications SET reminder_sent = 1 WHERE id = :id");
        $update->execute([':id' => $med['id']]);
    }
}

// =====================
// Appointments Reminder
// =====================
$sqlApps = "SELECT id, patient_name, doctor_name, appointment_date, location, reminder_time
            FROM appointments
            WHERE userName = :userName
              AND DATE(appointment_date) = :today
              AND reminder_sent = 0
              AND (reminder_time <= NOW() OR reminder_time IS NULL)";
$stmt = $db->prepare($sqlApps);
$stmt->execute([
    ':userName' => $userName,
    ':today' => $today
]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($appointments as $app) {
    $subject = "Appointment Reminder: {$app['appointment_date']}";
    $body = "Hi {$userName},<br><br>
             You have an upcoming appointment:<br>
             Patient: {$app['patient_name']}<br>
             Doctor: {$app['doctor_name']}<br>
             Date: {$app['appointment_date']}<br>
             Location: {$app['location']}<br><br>
             - CareTrack";

    if (sendEmail('user_email@example.com', $subject, $body)) {
        $update = $db->prepare("UPDATE appointments SET reminder_sent = 1 WHERE id = :id");
        $update->execute([':id' => $app['id']]);
    }
}

echo "Reminders processed successfully.";
?>
