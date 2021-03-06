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

  // 画像表示　
  $result = $user -> findByHome();
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
  <title>homeページ</title>
  <link rel="stylesheet" href="../css/home.css">
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
    <div class="user_posts">

      <?php foreach( (array) $result as $row): ?>
        <a class="post" href="post.php?id=<?= $row['id'] ?>">
          <img src="data:image/png;base64,<?php echo base64_encode($row['image']);?>" >
        </a>
      <?php endforeach; ?>
      <!-- ここはMySQLからさ印象処理の繰り返しを入れる-->

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
    </div><!-- user_posts -->
  </div><!-- main -->
</body>
</html>
