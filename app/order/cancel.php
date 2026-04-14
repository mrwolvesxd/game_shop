<?php
require_once '../../config/database.php';
require_once '../../helpers/auth_helper.php';
requireMember();

if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    // Verify ownership and pending status
    $stmt = $pdo->prepare("SELECT user_id, status FROM purchase WHERE purchase_id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    if ($order && $order['user_id'] == $_SESSION['user_id'] && $order['status'] == 'pending') {
        $update = $pdo->prepare("UPDATE purchase SET status = 'cancelled', status_updated_at = NOW(), cancel_reason = 'Cancelled by user' WHERE purchase_id = ?");
        $update->execute([$order_id]);
        $_SESSION['order_msg'] = 'Order cancelled successfully.';
    } else {
        $_SESSION['order_msg'] = 'Cannot cancel this order.';
    }
}
header('Location: order_history.php');
exit();
?>