<?php require '_base.php'; ?>

<?php
if ($_SESSION['user']['role'] != 'Admin') {
    echo "Access denied!";
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM user WHERE user_id=?");
$stmt->execute([$id]);

header("Location: member_list.php");
exit;
?>