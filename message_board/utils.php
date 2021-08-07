<?php
require_once("conn.php");

function getUserFromUsername($username)
{
  global $conn;
  $sql = sprintf(
    "select * from `andrea_users` where username = '%s'",
    $username
  );
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  return $row; // 會有username, id, nickname, role
}

function generateToken()
{
  $s = '';
  for ($i = 1; $i <= 16; $i++) {
    $s .= chr(rand(65, 90));
  }
  return $s;
}

function escape($str)
{
  return htmlspecialchars($str, ENT_QUOTES);
}

function error($errCode)
{
  switch ($errCode) {
    case ('1'):
      $msg = '資料不齊全';
      break;
    case ('2'):
      $msg = '帳號已被註冊!!';
      break;
    case ('3');
      $msg = '沒有權限';
      break;
    case ('4'):
      $msg = '請重新登入';
      break;
    case ('6'):
      $msg = '帳號密碼錯誤';
      break;
    default:
      $msg = '系統錯誤請稍後再試';
  }
  echo '<h2>錯誤： ' . $msg . '</h2>';
}
