<?php
require_once '../../config/database.php';
require_once '../../helpers/auth_helper.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    if (!in_array($new_status, $allowed)) {
        die('Invalid status.');
    }
    $stmt = $pdo->prepare("UPDATE purchase SET status = ?, status_updated_at = NOW() WHERE purchase_id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: order_detail.php?id=$order_id&msg=updated");
    exit();
}
?>