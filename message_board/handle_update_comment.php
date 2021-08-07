<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if (empty($_POST['content']) || empty($_POST['id'])) {
  header('Location: update_comment.php?errCode=1&id=' . $_POST['id']);
  die();
}

if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
} else {
  header('Location: index.php?errCode=4');
  die();
}

$id = $_POST['id'];
$content = $_POST['content'];


if ($user['role'] === "ADMIN") {
  $sql = "update `andrea_comments` set content=? where id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('si', $content, $id);
} else if ($user['role'] === "USER"){
  $sql = "update `andrea_comments` set content=? where id=? and username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sis', $content, $id, $username);
} else {
  header('Location: index.php?errCode=4');
  die();
}

$result = $stmt->execute();
if (!$result) {
  die($conn->error);
}

header("Location: index.php");
