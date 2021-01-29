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

  print_r($_GET);

  $result = $user -> findByPOST($_GET['id']);

  if ($_POST) {
    print_r($_POST);
    if (empty($_POST["category"])) {
      $message['category'] = 'カテゴリーを入力してください';
    }else {
      // $user->editbypost($_POST);
      $user = 'root';
      $pass = 'root';
      $dbh = new PDO('mysql:host=localhost;dbname=my_php', $user, $pass,$driver_options);

      $path = file_get_contents($_FILES['image']['tmp_name']);
      $category = $_POST["category"];

      $sql = "UPDATE posts SET image = :image, detail_text = :detail_text, category_id = :category_id WHERE id = :id";
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(':image', $path);
      $stmt->bindValue(':detail_text', $_POST['detail_text']);
      $stmt->bindValue(':id', $_GET["id"]);
      $stmt->bindValue(':category_id', $category);
      $stmt->execute();

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
  <title>投稿編集ページ</title>
  <link rel="stylesheet" href="../css/post_change.css">
  <link rel="stylesheet" href="../css/all_navi.css">
  <!-- <link rel="stylesheet" href="../css/bootstrap.css"> -->
  <script type="text/javascript" src="../js/jquery.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <!-- <script type="text/javascript" src="../js/bootstrap.js"></script> -->
  <script>
  $(function(){
    $('.dropdwn li').hover(function(){
      $("ul:not(:animated)", this).slideDown();
    }, function(){
      $("ul.dropdwn_menu",this).slideUp();
    });
  });

  $(function(){
    //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
    $('form').on('change', 'input[type="file"]', function(e) {
      var file = e.target.files[0],
      reader = new FileReader(),
      $preview = $(".preview");
      t = this;

      // 画像ファイル以外の場合は何もしない
      if(file.type.indexOf("image") < 0){
        return false;
      }

      // ファイル読み込みが完了した際のイベント登録
      reader.onload = (function(file) {
        return function(e) {
          //既存のプレビューを削除
          $preview.empty();
          // .prevewの領域の中にロードした画像を表示するimageタグを追加
          $preview.append($('<img>').attr({
            src: e.target.result,
            width: "400px",
            class: "preview",
            title: file.name
          }));
        };
      })(file);

      reader.readAsDataURL(file);
    });
  });


  $(function(){
    $(".categorysGroup").on("click", function(){
      $('.categorysGroup').prop('checked', false);  //  全部のチェックを外す
      $(this).prop('checked', true);  //  押したやつだけチェックつける
    });
  });

  </script>
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

</head>
<body>
  <div class="menu">
    <?php
    require("all_navi.php");
    ?>
  </div><!-- menu -->

  <div class="main">
    <h2>投稿編集</h2>
    <?php foreach( (array) $result as $row): ?>
      <div class="image_content">
        <img id="image" src="data:image/png;base64,<?php echo base64_encode($row['image']);?>" >
        <img id="yazirusi" src="../img/yazirusi.png" alt="">
        <div class="preview"></div>
      </div>


      <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['post_id'] ?>">
        <input id="file" type="file" name="image" accept=".png, .jpg, .jpeg">
        <br>
        <textarea name="detail_text" rows="10" cols="50" ><?= $row['detail_text'] ?></textarea>
        <br>
      <?php endforeach; ?>

      <?php if(isset($message['category']))echo "<p class ='error'>".$message['category']."</p>";?>

      <div class="form_category">

        <p class="categorys">CATEGORY1<br>
          <input type="checkbox" name="category" class="categorysGroup" value="1"> その1<br>
          <input type="checkbox" name="category" class="categorysGroup" value="2"> その2<br>
        </p>

        <p class="categorys">CATEGORY2<br>
          <input type="checkbox" name="category" class="categorysGroup" value="3"> その3<br>
          <input type="checkbox" name="category" class="categorysGroup" value="4"> その4<br>
        </p>

        <p class="categorys">CATEGORY3<br>
          <input type="checkbox" name="category" class="categorysGroup" value="5"> その5<br>
          <input type="checkbox" name="category" class="categorysGroup" value="6"> その6<br>
        </p>

        <p class="categorys">CATEGORY4<br>
          <input type="checkbox" name="category" class="categorysGroup" value="7"> その7<br>
          <input type="checkbox" name="category" class="categorysGroup" value="8"> その8<br>
        </p>

        <p class="categorys">CATEGORY5<br>
          <input type="checkbox" name="category" class="categorysGroup" value="9"> その9<br>
          <input type="checkbox" name="category" class="categorysGroup" value="10"> その10<br>
        </p>

      </div><!-- form_category -->


      <input id="submit" type="submit" name="" value="変　更">

    </form>

    <a class="return" href="users.php">戻　る</a>
  </div><!-- main -->

</body>
</html>
