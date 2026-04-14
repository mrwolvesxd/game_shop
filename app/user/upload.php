<?php
require_once '../../config/database.php';
require_once '../../helpers/auth_helper.php';
requireMember();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $file = $_FILES['profile_photo'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        if (!in_array($file['type'], $allowed)) {
            $errors[] = 'Only JPG, PNG, GIF allowed.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'File size must be < 2MB.';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $dest = '../../assets/uploads/profile_photos/' . $newName;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                // Delete old photo if not default
                $stmt = $pdo->prepare("SELECT profile_photo FROM user WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $old = $stmt->fetchColumn();
                if ($old && $old != 'default.png' && file_exists('../../assets/uploads/profile_photos/' . $old)) {
                    unlink('../../assets/uploads/profile_photos/' . $old);
                }
                $update = $pdo->prepare("UPDATE user SET profile_photo = ? WHERE user_id = ?");
                $update->execute([$newName, $_SESSION['user_id']]);
                $_SESSION['profile_success'] = 'Photo updated.';
            } else {
                $errors[] = 'Upload failed.';
            }
        }
    } else {
        $errors[] = 'No file or upload error.';
    }
}
$_SESSION['profile_errors'] = $errors;
header('Location: profile.php');
exit();
?>