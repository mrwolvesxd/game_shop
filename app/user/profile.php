<?php
require_once '../../config/database.php';
require_once '../../helpers/auth_helper.php';
require_once '../../libs/base_lib.php';

requireMember();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    
    // Validations
    if (empty($current)) $errors[] = 'Current password is required.';
    if (empty($new)) $errors[] = 'New password is required.';
    if ($new !== $confirm) $errors[] = 'New password and confirmation do not match.';
    if (strlen($new) < 6) $errors[] = 'New password must be at least 6 characters.';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT password FROM user WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if (password_verify($current, $user['password'])) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            if ($update->execute([$hashed, $_SESSION['user_id']])) {
                $success = 'Password changed successfully.';
            } else {
                $errors[] = 'Database error.';
            }
        } else {
            $errors[] = 'Current password is incorrect.';
        }
    }
}
// Redirect back with messages
session_start();
$_SESSION['profile_errors'] = $errors;
$_SESSION['profile_success'] = $success;
header('Location: profile.php');
exit();
?>