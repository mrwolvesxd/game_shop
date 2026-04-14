<?php require '_base.php'; ?>
<?php $_title = "Member List"; ?>
<?php require '_head.php'; ?>

<?php
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red;'>Please login first!</p>";
    exit;
}

if ($_SESSION['user']['role'] != 'Admin') {
    echo "<p style='color:red;'>Access denied!</p>";
    exit;
}

$search = $_GET['search'] ?? '';

if ($search != "") {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM user");
}

$users = $stmt->fetchAll();
?>

<h2>Member List</h2>

<form method="GET">
    <input name="search" placeholder="Search username">
    <button>Search</button>
</form>

<br>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Email</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['user_id'] ?></td>
    <td><?= $u['username'] ?></td>
    <td><?= $u['email'] ?></td>
    
    <td>
        <a href="delete_user.php?id=<?= $u['user_id'] ?>"
           onclick="return confirm('Are you sure you want to delete this user?')">
           Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require '_foot.php'; ?>