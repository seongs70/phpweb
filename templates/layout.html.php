<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/public/jokes.css">
    <title><?=$title?></title>
  </head>
  <body>
  <nav>
    <header>
      <h1>phpweb</h1>
    </header>
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="index.php?route=joke/list">글 목록</a></li>
      <li><a href="index.php?route=joke/edit">글 등록</a></li>
      <?php if ($loggedIn): ?>
          <li><a href="index.php?route=logout">로그아웃</a></li>
      <?php else: ?>
          <li><a href="index.php?route=login">로그인</a></li>
      <?php endif; ?>
    
    </ul>
  </nav>

  <main>
  <?=$output?>
  </main>

  <footer>
  &copy; IJDB 2017
  </footer>
  </body>
</html>
