<?php
session_start();

//config.phpを読み込み（インクルード）
require_once("../config/config.php");
require_once("../model/user.php");

//ログイン画面を経由しているか確認する
//セットされていなかったらリダイレクトでlogin.phpに戻る
if (!isset($_SESSION['user']['id'])) {
  header('Location: /webapp-php/php/login.php');
  exit;
}else {
  echo "セッション成功";
  echo $_SESSION['user']['id'];
}
try{
  //1.MYSQLへの接続(オブジェクト指向で)
  $user = new user($host,$dbname,$user,$pass,$driver_options);
  $user->connectDB();
  echo "接続完了";
  //画像の登録処理
  if ($_POST) {
    $user = 'root';
    $pass = 'root';
    $dbh = new PDO('mysql:host=localhost;dbname=my_php', $user, $pass,$driver_options);
    $path = file_get_contents($_FILES['image']['tmp_name']);
    $user_id = $_SESSION['user']['id'];
    $category = $_POST["category"];
    $datetime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO posts(image, detail_text, user_id, category_id, created_at)
    VALUES (:image, :detail_text, :user_id, :category_id, :created_at)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':image', $path);
    $stmt->bindValue(':detail_text', $_POST['detail_text']);
    $stmt->bindValue(':user_id', $user_id);
    $stmt->bindValue(':category_id', $category);
    $stmt->bindValue(':created_at', $datetime);
    $stmt->execute();
    echo "登録完了";
    print_r($_POST);
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
  <title>追加投稿ページ</title>
  <link rel="stylesheet" href="../css/new_post.css">
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

//チェックボックスの選択
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
    <h2>追加投稿</h2>
    <div class="preview"></div>

    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="image" accept=".png, .jpg, .jpeg">
      <br>
      <textarea name="detail_text" rows="10" cols="50" >キャプションを入力</textarea>
      <br>

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
      <input id="submit" type="submit" name="" value="投　稿">
    </form>

    <a class="return" href="users.php">戻　る</a>
  </div><!-- main -->
</body>
</html>
