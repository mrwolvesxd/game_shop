<?php require '_base.php'; ?>
<?php require '_head.php'; ?>

<?php
if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO user (username,email,password,full_name,role,profile_photo)
    VALUES (?,?,?,?, 'Member','')");

    $stmt->execute([
        $_POST['username'],
        $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['full_name']
    ]);

    echo "Success!";
}
?>

<h2>Register</h2>
<form method="POST">
    Username: <input name="username"><br>
    Full Name: <input name="full_name"><br>
    Email: <input name="email"><br>
    Password: <input type="password" name="password"><br>
    <button>Register</button>
</form>

<?php require '_foot.php'; ?>