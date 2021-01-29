<?php
// session_start();


//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");

try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass);
  $user->connectDb();
  echo "接続完了";

  //登録処理
  // もしもポストが飛んできたらユーザ情報を保存する
  if ($_POST) {
      $message = $user ->validate($_POST);
      if (empty($message['name']) && empty($message['email']) && empty($message['password'])) {
        $user->add($_POST);
        header('Location: /webapp-php/php/login.php');
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
  <title>新規登録ページ</title>
  <link rel="stylesheet" href="../css/new_user.css">
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
      <?php if(isset($message['name']))echo "<p class ='error'>".$message['name']."</p>";?>
      <?php if(isset($message['email']))echo "<p class ='error'>".$message['email']."</p>";?>
      <?php if(isset($message['password']))echo "<p class ='error'>".$message['password']."</p>";?>

      <form action="" method="post">
        <dl class="clearfix">
          <dt>アカウント名</dt>
          <dd><input type="text" name="name" value="" class="text_must" ></dd>
        </dl>
        <dl class="clearfix">
          <dt>メールアドレス</dt>
          <dd><input type="text" name="email" value="" class="text_must" ></dd>
        </dl>
        <dl class="clearfix">
          <dt>パスワード</dt>
          <dd><input type="password" name="password" value="" class="text_must" ></dd>
        </dl>
        <dl class="clearfix">
          <input type="submit" value="Sign Up" id="submit_btn">
        </dl>
      </form>

      <!-- <form action="" method="post">
            <input type="text" name="user_name" size="20" value="<?php if(isset($result['User'])) echo $result['User']['user_name']; ?>"></label>
            <input type="text" name="email" size="40" value="<?php if(isset($result['User'])) echo $result['User']['email']; ?>"></label>
            <input type="password" name="password" size="20" value="<?php if(isset($result['User'])) echo $result['User']['password']; ?>"></label>
            <input type="hidden" name="id" value="<?php if(isset($result['User'])) echo $result['User']['id'] ?>">
            <input type="submit" value="送 信">
          </form> -->
      <p>
        <a href="login.php">ログインはこちら</a>
      </p>
    </div>
  </div>
</body>
</html>
