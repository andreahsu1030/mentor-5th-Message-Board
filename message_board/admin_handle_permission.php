<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if (!$_SESSION['username']) {
  header("Location: index.php?errCode=4");
  die();
} 

$user = getUserFromUsername($_SESSION['username']);
if ($user['role'] !== "ADMIN") {
  header("Location: index.php?errCode=3");
  die();
}


$username = $_GET['username'];
$role = $_POST['role-form'];
echo $role;
if (empty($username) || empty($role)) {
  header("Location: admin.php");
  die("請重新選擇");
}
$sql = "update `andrea_users` set role=? where username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $role, $username);

$result = $stmt->execute();
if (!$result) {
  die($conn->error);
}

header("Location: admin.php");