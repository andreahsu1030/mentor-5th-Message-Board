<?php
session_start();
require_once("conn.php");
require_once("utils.php");

if (empty($_POST['nickname'])) {
  header('Location: login.php?errCode=1');
  die();
}

if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
} else {
  header('Location: index.php?errCode=4');
  die();
}
$nickname = $_POST['nickname'];

$sql = "update `andrea_users` set nickname=? where username=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $nickname, $username);

$result = $stmt->execute();
if (!$result) {
  die($conn->error);
}

header('location:index.php');
