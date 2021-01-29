<?php
require_once("DB.php");


class user extends DB {

  //ログインメソッド
  public function login($arr){
    $sql = 'SELECT * FROM users WHERE name = :name AND password = :password';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'=>$arr['name'],
      ':password'=>$arr['password']
    );
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //登録 insert アカウント登録
  public function add($arr){
    $sql = "INSERT INTO users (name, email, password, created_at) VALUES (:name, :email, :password, :created_at)";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'=>$arr['name'],
      ':email'=>$arr['email'] ,
      ':password'=>$arr['password'] ,
      ':created_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
    echo "登録完了";
  }

  //参照処理（条件付き） SELECT userページの画像表示の参照処理
  public function findByID($user_id){
    $sql = "SELECT image , id FROM posts WHERE user_id = $user_id order by created_at desc";
    $stmt = $this->connect->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  //参照 select change_userのuser情報の参照処理
  public function findByUser($id){
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  }

  //参照処理（条件付き） SELECT POSTページの参照処理
  public function findByPOST($id){
    $sql = "SELECT ";
    $sql .="user_id,";
    $sql .="posts.id AS post_id,";
    $sql .="image,";
    $sql .="detail_text,";
    $sql .="category_id	,";
    $sql .="categories.name AS category_name";
    $sql .=" FROM posts";
    $sql .=" JOIN categories ON posts.category_id = categories.id";
    $sql .=" WHERE posts.id = $id";
    $stmt = $this->connect->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  //参照処理（条件付き） SELECT HOMEページの参照処理
  public function findByHome(){
    $sql = "SELECT image , id FROM posts order by created_at DESC ";
    $stmt = $this->connect->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  //参照処理（条件付き） SELECT categoryページの参照処理
  public function findByCategoryPOST($id){
    $sql = "SELECT id , image FROM posts WHERE category_id = :category_id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':category_id'=>$id);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  //ユーザー情報の編集 update
  public function edit($arr){
    $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':id'=>$arr['id'],
      ':name'=>$arr['name'],
      ':email'=>$arr['email'] ,
      ':password'=>$arr['password']
    );
    $stmt->execute($params);
  }

  //POSTの編集 UPDATE
  public function editbypost($arr){
    $path = file_get_contents($_FILES['image']['tmp_name']);
    $category = implode(",", $arr["category"]);
    $sql = "UPDATE posts SET image = :image, detail_text = :detail_text, category_id = :category_id WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':id'=>$arr['id'],
      ':image'=>$path,
      ':detail_text'=>$arr['detail_text'] ,
      ':category_id'=>$category
    );
    $stmt->execute($params);
  }


  // //削除 delete
  public function delete($id){
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' =>$id);
    $stmt->execute($params);
  }


  //入力チェック validate
  public function validate($arr){
    $message = array();
    if (empty($arr['name'])) {
      $message['name'] = 'ユーザー名を入力してください';
    }
    if (empty($arr['email'])) {
      $message['email'] = 'メールアドレスを入力してください';
    }
    else {
      if (!filter_var($arr['email'], FILTER_VALIDATE_EMAIL)) {
        $message['email'] = 'メールアドレスが正しくありません';
      }
    }
    if (empty($arr['password'])) {
      $message['password'] = 'パスワードを入力してください';
    }
    return $message;
  }


  //入力チェック リクエスト validate
  public function validate_request($arr){
    $message = array();
    //タイトル
    if (empty($arr['title'])) {
      $message['title'] = 'タイトルを入力してください';
    }
    //内容
    if (empty($arr['content'])) {
      $message['content'] = 'リクエスト内容を入力してください';
    }
    return $message;
  }
}

?>
