<?php
session_start();
session_destroy();
// require_once('conn.php');

// // 刪除 token
// $token = $_COOKIE['token'];
// $sql = sprintf(
//   "delete from tokens where token='%s'",
//   $token
// );
// $conn->query($sql);

//   // 將 token 在 cookie 設為空的
//   setcookie("token", "", time() - 3600);
  header("Location: index.php");
?>