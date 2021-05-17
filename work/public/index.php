<?php
// 絶対パスからfunctions.phpを読み込み
require_once(__DIR__ . '/../app/functions.php');


// $_POST連想配列の中にsubmitという名前のキーが存在し、NULL以外の値がセットされているか確認
if(isset($_POST['submit'])) {
  $name = $_POST['title'];
  $name = htmlspecialchars($name, ENT_QUOTES);

  // データベースの接続とデータの取得(functions.php内の関数)
  $dbh = db_connect();
  
  // title,is_doneの値をデータベースに挿入。is_doneの初期値は0。
  $sql = 'INSERT INTO tasks (title, is_done) VALUE (?, 0)';
  // PDOインスタンスのprepareメソッドを$sqlを渡して呼び出し。
  $stmt = $dbh->prepare($sql);
  // ?にtitleとユーザから入力されたタスク名の値を紐付ける
  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  // SQL文の実行、テーブルへデータ格納
  $stmt->execute();
  $dbh = null;
  unset($name);
}

if(isset($_POST['method']) && ($_POST['method'] === 'put')) {
  $id = $_POST["id"];
  $id = htmlspecialchars($id, ENT_QUOTES);
  $id = (int)$id;

  $dbh = db_connect();

  $sql = 'UPDATE tasks SET is_done = 1 WHERE id = ?';
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(1, $id, PDO::PARAM_INT);
  $stmt->execute();

  $bdh = null;
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>英語の勉強リスト</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <h1>英語の勉強リスト</h1>
  <form action="index.php" method="post">
    <ul>
      <li><span>タスク名</span><input type="text" name="title"></li>
      <li><input type="submit" name="submit"></li>
    </ul>
  </form>

  <ul>
  <?php

    $dbh = db_connect();
    
    // SQL文：idカラムの降順（新しいもの順）で出す
    // doneがまだputされていない(is_done=0)ものを抽出
    $sql = 'SELECT id, title FROM tasks WHERE is_done = 0 ORDER BY id DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbh = null;

    // fetchメソッドで、連想配列の形で取得する
    while($task = $stmt->fetch(PDO::FETCH_ASSOC)) {

      print '<li>';
      print $task['title'];

      print '
      <form action="index.php" method="post">
        <input type="hidden" name="method" value="put">
        <input type="hidden" name="id" value="' . $task['id'] . '">
        <button type="submit">終わった</button>
      </form>
      ';

      print '</li>';

    }
  ?>
  </ul>

</body>
</html>