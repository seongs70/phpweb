<style>
.jokelist {display:table;}
.categories{
    display:table-cell; width:20%;
    background-color:#dedede; padding:1em;
    list-style-type:none;
}
.categories a {color:white; text-decoration:none;}
.categories li {margin-bottom:1em;}
.jokelist .jokes {display:table-cell; padding:1em;}
</style>
<div class="jokeList">
    <ul class="categories">
        <?php foreach ($categories as $category): ?>
        <li>
            <a href="index.php?route=joke/list&category=<?=$category->id?>"><?=$category->name?></a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="jokes">
    <p><?=$totalJokes?>개 글이 있습니다.</p>

<?php foreach($jokes as $joke): ?>

<blockquote>
  <p>
  <?=htmlspecialchars($joke->joketext, ENT_QUOTES, 'UTF-8')?>

    (작성자: <a href="mailto:<?=htmlspecialchars($joke->getAuthor()->email, ENT_QUOTES,
                    'UTF-8'); ?>">
                <?=htmlspecialchars($joke->getAuthor()->name, ENT_QUOTES,
                    'UTF-8'); ?></a> 작성일:
<?php

                $date = new DateTime($joke->jokedate);

                echo $date->format('jS F Y');
?>)

<?php if ($user): ?>
  <?php if ($user->id == $joke->authorId || $user->hasPermission(\Ijdb\Entity\Author::EDIT_JOKES)): ?>

  <a href="index.php?route=joke/edit&id=<?=$joke->id?>">수정</a>
  <?php endif; ?>
  <?php if ($user->id == $joke->authorId || $user->hasPermission(\Ijdb\Entity\Author::DELETE_JOKES)): ?>
  <form action="index.php?route=joke/delete" method="post">
    <input type="hidden" name="id" value="<?=$joke->id?>">
    <input type="submit" value="삭제">
  </form>
  <?php endif; ?>
<?php endif; ?>
  </p>
</blockquote>
<?php endforeach; ?>

</div>
