<?php
session_start();

//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");

try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass);
  $user->connectDb();
  echo "接続完了";

// //ログインのフォームの操作
  if ($_POST) {
    $result = $user->login($_POST);
    if(!empty($result)){
      $_SESSION['user'] = $result;
      header('Location: /webapp-php/php/users.php');
      exit;
    }
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
  <title>ログインページ</title>
  <link rel="stylesheet" href="../css/login.css">
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
  </div>
  <div class="main">
    <div class="contents">
      <h1>画像</h1>
    </div>
    <div class="contents">
      <h1>Hello!!</h1>
      <form action="" method="post">
        <dl class="clearfix">
          <dt>アカウント名</dt>
          <dd><input type="text" name="name" value="" class="text_must" ></dd>
        </dl>
        <dl class="clearfix">
          <dt>パスワード</dt>
          <dd><input type="password" name="password" value="" class="text_must" ></dd>
        </dl>
        <dl class="clearfix">
          <input type="submit" value="Sign Up" id="submit_btn">
        </dl>
      </form>
    </div>
  </div>
</body>
</html>
