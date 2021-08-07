<?php
session_start();
require_once("conn.php");
require_once("utils.php");

if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $user_role = $user['role']; // 拿到登入者的role
} else {
  header("Location: index.php?errCode=4");
  die();
}

if ($user_role !== "ADMIN") {
  header("Location: index.php?errCode=3");
  die();
}

$result = $conn->query("select * from `andrea_users`");
if (!$result) {
  die($conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Document</title>
</head>

<body>
  <header class="warning">注意！本站為練習用網站，音教學用途刻意忽略資安實作，註冊時請勿使用任何真實的帳號密碼。</header>
  <main class="board">
    <a class="board__btn" href="logout.php">登出</a>
    <a class="board__btn" href="index.php">回到留言板</a>
    <h1>後台管理</h1>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <div class="admin-card">
        <div class="card__author">
          <?php echo escape($row['nickname']); ?>
          (@<?php echo escape($row['username']); ?>)<br>
          <div class="default-style"><?php echo "目前狀態：  " . escape($row['role']); ?></div>
        </div>
        <form class="select-form" action="admin_handle_permission.php?username=<?php echo escape($row['username']) ?>" method="POST">
          <select name="role-form">
            <option value="USER">USER</option>
            <option value="ADMIN">ADMIN</option>
            <option value="BLOCKED">BLOCKED</option>
          </select>
          <input type="submit">
      </div>
      <div class="border__hr"></div>
      </form>
    <?php } ?>

  </main>
</body>

</html>