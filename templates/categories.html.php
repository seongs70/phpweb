
<h2>카테고리</h2>

<a href="index.php?route=category/edit">카테고리 추가</a>

<?php foreach($categories as $category): ?>
<blockquote>
  <p>
  <?=htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8')?>

  <a href="index.php?route=category/edit&id=<?=$category->id?>">수정</a>
  <form action="index.php?route=category/delete" method="post">
    <input type="hidden" name="id" value="<?=$category->id?>">
    <input type="submit" value="삭제">
  </form>
  </p>
</blockquote>
<?php endforeach; ?>
