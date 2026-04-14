<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

$pdo = new PDO("mysql:host=localhost;dbname=game_shop", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
?>