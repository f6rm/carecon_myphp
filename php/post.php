<?php
session_start();
print_r($_SESSION['user']["id"]);
print_r($_GET['id']);
//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");
//ログイン画面を経由しているか確認する
//セットされていなかったらリダイレクトでlogin.phpに戻る
if (!isset($_SESSION['user'])) {
  header('Location: /webapp-php/php/login.php');
  exit;
}
try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass);
  $user->connectDb();
  echo "接続完了";

  if (isset($_GET['del'])) {
    //画像の削除処理
    $user->delete($_GET['del']);
    header('Location: /webapp-php/php/users.php');
    exit;
  }
  // 画像表示　
  $result = $user -> findByPOST($_GET['id']);

}
//tryおわり
//DBに異常があった場合検知（キャッチ）できるようにしておく
catch (PDOException $e) { // PDOExceptionをキャッチする
  print "エラー!: " . $e->getMessage() . "<br/gt;";
  die();
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>投稿ページ</title>
  <link rel="stylesheet" href="../css/post.css">
  <link rel="stylesheet" href="../css/all_navi.css">
  <script type="text/javascript" src="../js/jquery.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script>
  $(function(){
    $('.dropdwn li').hover(function(){
      $("ul:not(:animated)", this).slideDown();
    }, function(){
      $("ul.dropdwn_menu",this).slideUp();
    });
  });
  </script>
</head>
<body>
  <div class="menu">
    <?php
    require("all_navi.php");
    ?>
  </div><!-- menu -->
  <div class="main">
    <?php foreach( (array) $result as $row): ?>
      <div class="contents">
        <img src="data:image/png;base64,<?php echo base64_encode($row['image']);?>" >
      </div>

      <div class="contents">
        <div class="post_text">
          <p rows="7" cols="40"><?= $row['detail_text'] ?></p>
        </div>
        <div class="post_category">
          <h3>カテゴリー</h3>
          <p><?= $row['category_name'] ?></p>
        </div>
        <div class="post_data">
          <?php if ($_SESSION['user']["id"] == $row['user_id']): ?>
            <a href="post_change.php?id=<?= $row['post_id'] ?>">編集</a>
            <?php if ($_SESSION['user']["id"] == $row['user_id']): ?>
              <a href="?del=<?= $row['post_id'] ?>" onClick="if(!confirm('ID<?= $row['post_id'] ?>を削除しますか？'))return false">削除</a>
            <?php endif; ?>
          <?php endif; ?>


          <!-- セッション時のみ表示しない -->
          <?php if ($_SESSION['user']["id"] != $row['user_id']): ?>
            <a id="nosession" href="users.php?id=<?= $row['user_id'] ?>">ユーザーページに移動する</a>
          <?php endif; ?>

        </div><!-- post_data -->

      </div><!-- contents -->
    <?php endforeach; ?>


  </div><!-- main -->

</body>
