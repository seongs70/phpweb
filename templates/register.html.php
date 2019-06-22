
<style>
.errors{
    padding:1em; border:1px solid red;
    background-color:lightyellow; color:red;
    margin-bottom:1em; overflow:auto;
}
.errors ul {
    margin-left:1em;
}
</style>
<?php
if(!isset($_SESSION['visits'])){
    $_SESSION['visits'] = 0;
}
$_SESSION['visits'] = $_SESSION['visits'] + 1;
if($_SESSION['visits'] > 1 ) {
    echo $_SESSION['visits'] . "번째 방문하셨습니다.";
} else {
    //첫 방문
    echo '웹사이트에 오신 걸 환영합니다. 둘려보려면 여기를 클릭하세요.';
}





if(!empty($errors)):
    if(empty($author['email'])){
        $valid = false;
        $errors[] = '이메일을 입력해야 합니다.';
    }
    else if (filter_var($author['email'],FILTER_VALIDATE_EMAIL) == false ) {
        $valid = false;
        $errors[] = '유효하지 않은 이메일 주소 입니다.';
    }
    ?>
    <div class="errors">
        <p>등록할 수 없습니다. 다음을 확인해 주세요.</p>
        <ul>
        <?php
            foreach($errors as $error):
            ?>
            <li><?= $error ?></li>
            <?php
            endforeach; ?>
        </ul>
    </div>
<?php
endif;


?>
<form action ="" method="post">
    <label for="">이메일</label>
    <input name="author[email]" id="email" type="text" value="<?=$author['email'] ?? ''?>" />
    <label for="">이름</label>
    <input name="author[name]" id="name" type="text" value="<?=$author['name'] ?? ''?>"/>
    <label for="">비밀번호</label>
    <input name="author[password]" id="password" type="text" value="<?=$author['password'] ?? ''?>"/>
    <input type="submit" name="submit" value="사용자등록" />

</form>
