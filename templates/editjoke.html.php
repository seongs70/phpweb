<!-- 본문과 id값은 $joke변수가 있을때 때만 출력된다.  -->
<form action="" method="post">
	<input type="hidden" name="joke[id]" value="<?=$joke['id'] ?? ''?>">
    <label for="joketext">유머 글을 입력해주세요: </label>
    <textarea id="joketext" name="joke[joketext]" rows="3" cols="40"><?=$joke['joketext'] ?? ''?></textarea>
    <input type="submit" name="submit" value="저장">
</form>
