<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if (empty($_GET['id'])) {
  header('Location: index.php?errCode=1');
  die();
}

if (empty($_SESSION['username'])) {
  header('Location: index.php?errCode=4');
  die();
}

$id = $_GET['id'];
$username = $_SESSION['username'];
$user = getUserFromUsername($username);

if ($user['role'] === "ADMIN") {
  $sql = "update `andrea_comments` set `is_deleted`=1 where id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
} else {
  $sql = "update `andrea_comments` set `is_deleted`=1 where id=? and username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('is', $id, $username);
}

$result = $stmt->execute();
if (!$result) {
  die($conn->error);
}

header("Location: index.php");
