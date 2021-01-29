<?php

session_start();

//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");

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

  if (isset($_SESSION['user']['id'])) {

    if ($_POST) {
      $message = $user ->validate($_POST);
      if (empty($message['name']) && empty($message['email']) && empty($message['password']) ) {
        $user->edit($_POST);
        header('Location: /webapp-php/php/users.php');
        exit;
      }
    }
    //参照処理
    $result = $user->findByUser($_SESSION['user']['id']);
  }

  //削除処理
  if (isset($_GET['del'])) {
    echo "aaaaa";
        $user->delete($_GET['del']);
        header('Location: /webapp-php/php/home.php');
        exit;
  }
  //編集処理
  // if ($_POST) {
  //   $user->edit($_POST);
  //   echo "編集処理";
  // }

  //ユーザー情報の特定
  // $result = $user->findByUser($_SESSION['user']['id']);
  // print_r($result);

  // }

  // $result = $user -> findByAll();

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
  <title>アカウント編集ページ</title>
  <link rel="stylesheet" href="../css/users_change.css">
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

  // $(function(){
  //   //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
  //   $('form').on('change', 'input[type="file"]', function(e) {
  //     var file = e.target.files[0],
  //     reader = new FileReader(),
  //     $preview = $(".preview");
  //     t = this;
  //
  //     // 画像ファイル以外の場合は何もしない
  //     if(file.type.indexOf("image") < 0){
  //       return false;
  //     }
  //
  //     // ファイル読み込みが完了した際のイベント登録
  //     reader.onload = (function(file) {
  //       return function(e) {
  //         //既存のプレビューを削除
  //         $preview.empty();
  //         // .prevewの領域の中にロードした画像を表示するimageタグを追加
  //         $preview.append($('<img>').attr({
  //           src: e.target.result,
  //           width: "300px",
  //           class: "preview",
  //           title: file.name
  //         }));
  //       };
  //     })(file);
  //
  //     reader.readAsDataURL(file);
  //   });
  // });

  </script>

</head>
<body>
  <div class="menu">
    <?php
    require("all_navi.php");
    ?>
  </div><!-- menu -->

  <div class="main">

    <div class="contents">
      <h1>画像</h1>
    </div>
    <div class="contents">
      <h1>アカウント情報の変更</h1>
      <?php if(isset($message['name']))echo "<p class ='error'>".$message['name']."</p>";?>
      <?php if(isset($message['email']))echo "<p class ='error'>".$message['email']."</p>";?>
      <?php if(isset($message['password']))echo "<p class ='error'>".$message['password']."</p>";?>

      <form action="" method="post">
        <dl class="clearfix">
          <dt>アカウント名</dt>
          <dd><input type="text" name="name" size="20" class="text_must" value="<?php if(isset($result)) echo $result['name']; ?>"></dd>
        </dl>
        <dl class="clearfix">
          <dt>メールアドレス</dt>
          <dd><input type="text" name="email" size="40" class="text_must" value="<?php if(isset($result)) echo $result['email']; ?>"></dd>
        </dl>
        <dl class="clearfix">
          <dt>パスワード</dt>
          <dd><input type="password" name="password" size="20" class="text_must" value="<?php if(isset($result)) echo $result['password']; ?>"></dd>
        </dl>
        <dl class="clearfix">
          <input type="hidden" name="id" value="<?php if(isset($result)) echo $result['id'] ?>">
          <input type="submit" value="変更" id="submit_btn">
        </dl>
      </form>
      <a href="?del=<?= $result['id'] ?>" onClick="if(!confirm('アカウント（<?= $result['name'] ?>）を削除しますか？'))return false">アカウントを削除する</a>

    </div>

    <!-- <form action="" method="post">
    <label>ユーザ名：<input type="text" name="name" size="20" value="<?php if(isset($result)) echo $result['name']; ?>"></label>
    <br>
    <label>メールアドレス：<input type="text" name="email" size="40" value="<?php if(isset($result)) echo $result['email']; ?>"></label>
    <br>
    <label>パスワード：<input type="password" name="password" size="20" value="<?php if(isset($result)) echo $result['password']; ?>"></label>
    <br>
    <input type="hidden" name="id" value="<?php if(isset($result)) echo $result['id'] ?>">
    <input type="submit" value="送 信">
  </form> -->
</div><!-- main -->




</div>
</body>
</html>
