
<div class="menu">
  <div class="header">
    <div class="logo">
      <h1><a href="home.php">ロゴ</a></h1>
    </div><!-- logo -->
    <div class="navi_menu">
      <ul>
        <li><a href="#">初めての方へ</a></li>
        <li><a href="users.php">マイページへ</a></li>
        <?php if(!isset($_SESSION['user'])): ?>
        <li><a href="new_user.php">新規登録</a></li>
        <li><a href="login.php">ログイン</a></li>
        <?php endif; ?>
      </ul>
    </div><!-- navi_menu -->
  </div><!-- header -->
  <div class="category">
    <ul class="dropdwn">
          <li>CATEGORY</li>
          <li>A
              <ul class="dropdwn_menu">
                  <li><a href="category.php?id=1">A1</a></li>
                  <li><a href="category.php?id=2">A2</a></li>
              </ul>
          </li>
          <li>B
              <ul class="dropdwn_menu">
                  <li><a href="category.php?id=3">B1</a></li>
                  <li><a href="category.php?id=4">B2</a></li>
              </ul>
          </li>
          <li>C
              <ul class="dropdwn_menu">
                  <li><a href="category.php?id=5">C1</a></li>
                  <li><a href="category.php?id=6">C2</a></li>

              </ul>
          </li>
          <li>D
              <ul class="dropdwn_menu">
                  <li><a href="category.php?id=7">D1</a></li>
                  <li><a href="category.php?id=8">D2</a></li>

              </ul>
          </li>
          <li>E
              <ul class="dropdwn_menu">
                  <li><a href="category.php?id=9">E1</a></li>
                  <li><a href="category.php?id=10">E2</a></li>
              </ul>
          </li>
      </ul>
  </div>

</div>
