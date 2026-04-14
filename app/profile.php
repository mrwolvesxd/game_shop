<?php require '_base.php'; ?>
<?php $_title = "Profile"; ?>
<?php require '_head.php'; ?>

<?php
if (!isset($_SESSION['user'])) {
    echo "<p style='color:red;'>Please login first!</p>";
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];

    if ($username == "" || $email == "") {
        echo "<p style='color:red;'>Please fill all fields!</p>";
    } else {

        $stmt = $pdo->prepare("UPDATE user SET username=?, email=? WHERE user_id=?");
        $stmt->execute([$username, $email, $user['user_id']]);

        echo "<p style='color:green;'>Profile updated!</p>";

        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['email'] = $email;
    }
}
?>

<h2>My Profile</h2>

<form method="POST">
    Username: <input name="username" value="<?= $user['username'] ?>"><br>
    Email: <input name="email" value="<?= $user['email'] ?>"><br>
    <button>Update</button>
</form>

<?php require '_foot.php'; ?>