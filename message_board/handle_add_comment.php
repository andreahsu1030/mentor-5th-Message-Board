<?php
session_start();
require_once("conn.php");
require_once("utils.php");


if (empty($_POST['content'])) {
  header('Location: index.php?errCode=1');
  die();
}
if (empty($_SESSION['username'])) {
  header('Location: index.php?errCode=4');
  die();
}


// $id = $_POST['id'];
$username = $_SESSION['username'];
$user = getUserFromUsername($username);
$content = $_POST['content'];

if ($user['role'] !== "BLOCKED") {
  $sql = "insert into `andrea_comments`(username, content) values(?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $username, $content);
}

// if ($user['role'] === "ADMIN") {
//   $sql = "insert into `andrea_comments`(content) values(?)";
//   $stmt = $conn->prepare($sql);
//   $stmt->bind_param('s', $content);
// }

$result = $stmt->execute();

if (!$result) {
  die('Error:' . $conn->error);
}

header("Location:index.php");
