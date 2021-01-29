<?php

session_start();

//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");

// //ログイン画面を経由しているか確認する
// //セットされていなかったらリダイレクトでlogin.phpに戻る
if (!isset($_SESSION['user'])) {
  header('Location: /webapp-php/php/login.php');
  exit;
}



try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass);
  $user->connectDb();
  echo "接続完了";

  //postページからユーザーページに遷移
  if (isset($_GET['id'])) {
    echo "リクエストから遷移";
    print_r($_GET['id']);
    //ユーザー情報の特定
    $user_name = $user->findByUser($_GET['id']);
    print_r($user_name["id"]);
    print_r($user_name["email"]);
    print_r($_SESSION['user']["email"]);
  }

  if (isset($_POST["send"])) {
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    $to = $user_name["email"];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $headers = $_SESSION['user']["email"];

    $message = $user ->validate_request($_POST);

    if (empty($message['title']) && empty($message['content'])) {
      if(mb_send_mail($to, $title, $content,$headers)){
        $alert = "<script type='text/javascript'>alert('リクエストを送信しました。');</script>";
        echo $alert;
        header('Location: /webapp-php/php/users.php');
        exit;
      } else {
        $alert = "<script type='text/javascript'>alert('リクエストの送信に失敗しました。');</script>";
        echo $alert;
      }
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
  <title>リクエストページ</title>
  <link rel="stylesheet" href="../css/request.css">
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

    <h2>リクエストフォーム</h2>

    <form action="" method="post">
      <p>
        宛先：<?php echo $user_name["name"]; ?>
      </p>
      <p>タイトル</p>
      <textarea name="title" cols="30" rows="2"></textarea>
      <p>内容</p>
      <textarea name="content" cols="50" rows="10"></textarea>

      <?php if(isset($message['title']))echo "<p class ='error'>".$message['title']."</p>";?>
      <?php if(isset($message['content']))echo "<p class ='error'>".$message['content']."</p>";?>
      <input type="submit" name="send" value="送信">

    </form>



  </div><!-- main-->
</body>
</html>
