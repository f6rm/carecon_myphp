<?php

session_start();

//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");
//ログアウト処理
if (isset($_GET['logout'])) {
  //セッション情報を破棄する
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか確認する
//セットされていなかったらリダイレクトでlogin.phpに戻る
if (!isset($_SESSION['user'])) {
  header('Location: /webapp-php/php/login.php');
  exit;
}elseif ($_POST) {
  echo "POST飛んできた";
}else {
  echo "セッション成功";
}



try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass);
  $user->connectDb();
  echo "接続完了";

  //postページからユーザーページに遷移
  if (isset($_GET['id'])) {
    echo "POSTから遷移";
    //ユーザー情報の特定
    $user_name = $user->findByUser($_GET['id']);
    print_r($user_name["id"]);

    // 画像表示　
    $result = $user -> findByID($_GET['id']);
  }
  //セッションからユーザーページに遷移
  elseif ($_SESSION['user']) {
    echo "セッションから遷移";
    //ユーザー情報の特定
    $user_name = $user->findByUser($_SESSION['user']['id']);
    print_r($user_name);

    // 画像表示　
    $result = $user -> findByID($_SESSION['user']['id']);
  }

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
  <title>アカウントページ</title>
  <link rel="stylesheet" href="../css/users.css">
  <link rel="stylesheet" href="../css/all_navi.css">
  <script type="text/javascript" src="../js/jquery.js"></script>
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

    <?php if (isset($_GET['id'])): ?>
      <div class="request">
      <h2 class="request_user"><?php echo $user_name['name']; ?>のページ</h2>
        <div class="request_info">
          <a href="request.php?id=<?= $user_name["id"] ?>">リクエスト</a>
        </div>
      </div><!-- request -->

    <?php elseif(isset($_SESSION['user'])): ?>
      <h2 class="session_user"><?php echo $user_name['name']; ?>のページ</h2>

      <div class="attribute_change">
        <div class="box">
          <a href="new_post.php">追加投稿</a>
        </div>

        <!-- <div class="box">
          <a href="request.php">リクエスト</a>
        </div> -->

        <div class="box">
          <a href="users_change.php">アカウント編集</a>
        </div>

        <div class="box">
          <a href="?logout=1">ログアウト</a>
        </div>
      </div><!-- attribute_change -->
    <?php endif; ?>



    <div class="user_posts">

      <?php foreach( (array) $result as $row): ?>
        <a class="post" href="post.php?id=<?= $row['id'] ?>">
          <img src="data:image/png;base64,<?php echo base64_encode($row['image']);?>" >
        </a>
      <?php endforeach; ?>


      <a class="post" href="#">
        <img src="../img/test_img.png" alt="">
      </a>
      <a class="post" href="#">
        <img src="../img/test_img.png" alt="">
      </a>
      <a class="post" href="#">
        <img src="../img/test_img.png" alt="">
      </a>
      <a class="post" href="#">
        <img src="../img/test_img.png" alt="">
      </a>
      <a class="post" href="#">
        <img src="../img/test_img.png" alt="">
      </a>
      <!-- ここはMySQLからさ印象処理の繰り返しを入れる-->
    </div><!-- user_posts -->


  </div><!-- main -->

</body>
</html>
