<?php
session_start();
require_once("conn.php");
require_once("utils.php");



$username = NULL;
$user = NULL;

if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
} else {
  header('Location: index.php?errCode=4');
  die();
}

if (empty($id = $_GET['id'])) {
  header('Location: index.php?errCode=5');
  die();
}

$stmt = $conn->prepare(
  'select * from `andrea_comments` where id = ?'
);
$stmt->bind_param("i", $id);
$result = $stmt->execute();
if (!$result) {
  die('Error:' . $conn->error);
}
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>留言板</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header class="warning">
    <strong>注意！本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼。</strong>
  </header>
  <main class="board">
    <h1 class="board__title">編輯留言</h1>
    <?php
    if (!empty($_GET['errCode'])) {
      error($_GET['errCode']);
    }
    ?>
    <form class="board__new-comment-form" method="POST" action="handle_update_comment.php">
      <textarea name="content" rows="5"><?php echo escape($row['content']) ?></textarea>
      <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
      <input class="board__submit-btn" type="submit" />
    </form>
  </main>
</body>

</html>