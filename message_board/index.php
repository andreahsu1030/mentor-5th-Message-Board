<?php
session_start();
require_once("conn.php");
require_once("utils.php");

$user = NULL;
$username = NULL;
$role = NULL;

if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
}


$page = 1;
if (!empty($_GET['page'])) {
  $page = intval($_GET['page']);
}
$items_per_page = 5;
$offset = ($page - 1) * $items_per_page;

$stmt = $conn->prepare(
  'select ' .
    'C.id as id, C.content as content, ' .
    'C.created_at as created_at, U.nickname as nickname, U.username as username ' .
    'from `andrea_comments` as C ' .
    'left join `andrea_users` as U on C.username = U.username ' .
    'where C.is_deleted IS NULL ' .
    'order by C.id desc ' .
    'limit ? offset ? '
);
$stmt->bind_param('ii', $items_per_page, $offset);
$result = $stmt->execute();
if (!$result) {
  die('Error:' . $conn->error);
}
$result = $stmt->get_result();
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
    <?php if (!$username) { ?>
      <div>
        <a class="board__btn" href="register.php">註冊</a>
        <a class="board__btn" href="login.php">登入</a>
      </div>
    <?php } else { ?>
      <a class="board__btn" href="logout.php">登出</a>
      <span class="board__btn update-nickname">編輯暱稱</span>
      <?php if ($user["role"] === "ADMIN") { ?>
        <a class="board__btn" href="admin.php">權限管理</a>
      <?php } ?>

      <form class="hide board__nickname-form board__new-comment-form" method="POST" action="update_user.php">
        <div class="board__nickname">
          <span>新的暱稱：</span><input name="nickname">
        </div>
        <input class="board__submit-btn" type="submit">
      </form>
      <h3>你好！<?php echo escape($user['nickname']); ?></h3>
    <?php } ?>


    <h1>comments</h1>
    <?php
    if (!empty($_GET['errCode'])) {
      error($_GET['errCode']);} 
    ?>

    <?php if (!$username) { ?>
      <h3>請登入發布留言 </h3>
    <?php } ?>
    
    <?php if ($username && $user['role'] !== "BLOCKED") { ?>
      <form class="board__new-comment-form" method="POST" action="handle_add_comment.php">
        <textarea name="content" rows="5"></textarea>
        <input class="board__submit-btn" type="submit">
      </form>

      <!-- 若帳號被封鎖 -->
      <?php if ($user['role'] == "BLOCKED") { ?>
        <h3>目前無法發布留言，請聯繫客服</h3>
      <?php } ?>
    <?php } ?>

    <div class="border__hr"></div>

    <section>

      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card">
          <div class="card__avatar"></div>
          <div class="card__body">
            <div class="card__info">
              <!-- php 替換成等於'=' 就是輸出的簡寫，但部分地方不支援 -->
              <span class="card__author">
                <?php echo escape($row['nickname']); ?>
                (@<?php echo escape($row['username']); ?>)
              </span>
              <span class="card__time"><?php echo $row['created_at']; ?></span>

              <?php if ($username) { ?>
                <?php if ($row['username'] === $username || $user["role"] == "ADMIN") { ?>
                  <!-- 前者的username是發文人的username, 後者則是登入者的 username -->
                  <a href="update_comment.php?id=<?php echo $row['id'] ?>"> 編輯 </a>
                  <a href="handle_delete_comment.php?id=<?php echo $row['id'] ?>"> 刪除 </a>
                <?php } ?>
              <?php } ?>
            </div>
            <p class="card__content"><?php echo escape($row['content']); ?></p>
          </div>
        </div>
      <?php } ?>

    </section>
    <div class="board__hr"></div>
    <?php
    $stmt = $conn->prepare(
      'select count(id) as count from `andrea_comments` where is_deleted IS NULL'
    );
    $result = $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $total_page = ceil($count / $items_per_page)
    ?>
    <div class="page_info">
      <span>總共有 <?php echo $count ?> 筆留言，頁數：</span>
      <span><?php echo $page ?> / <?php echo $total_page ?></span>
      分頁
    </div>
    <div class="paginator">
      <?php if ($page != 1) { ?>
        <a href="index.php?page=1">第一頁</a>
        <a href="index.php?page=<?php echo $page - 1 ?>">上一頁</a>
      <?php } ?>
      <?php if ($page != $total_page) { ?>
        <a href="index.php?page=<?php echo $page + 1 ?>">下一頁</a>
        <a href="index.php?page=<?php echo $total_page ?>">最後一頁</a>
      <?php } ?>
    </div>
  </main>
  <script>
    const btn = document.querySelector('.update-nickname')
    btn.addEventListener('click', function() {
      const form = document.querySelector('.board__nickname-form')
      form.classList.toggle('hide')
    })
  </script>
</body>

</html>