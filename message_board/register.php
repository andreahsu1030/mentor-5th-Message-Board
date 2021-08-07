<?php
require_once("utils.php");
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
    <div>
      <a class="board__btn" href="index.php">回留言板</a>
      <a class="board__btn" href="login.php">登入</a>
    </div>
    <h1>註冊</h1>
    <?php
    if (!empty($_GET['errCode'])) {
      error($_GET['errCode']);
    }
    ?>

    <form class="board__new-comment-form" method="POST" action="handle_register.php">

      <div class="board__nickname">
        <span>暱稱：</span><input name="nickname">
      </div>

      <div class="board__nickname">
        <span>帳號：</span><input name="username">
      </div>
      <div class="board__nickname">
        <span>密碼：</span><input name="password" type="password">
      </div>

      <input class="board__submit-btn" type="submit">
    </form>


  </main>
</body>

</html>