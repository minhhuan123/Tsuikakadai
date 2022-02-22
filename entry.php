<?php
session_start();
require_once("functions.php");
if($_SERVER['REQUEST_METHOD'] === 'POST'){
   if(!empty($_POST)){
        $email = htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8');
        $user = htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES, 'UTF-8');
        $password = password_hash($_SESSION['join']['password'],PASSWORD_DEFAULT);
        $picture = htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES, 'UTF-8');

        $dbh = db_conn();
        try{
   	        $sql = 'INSERT INTO members SET email=:email,user=:name,password=:password,picture=:picture,createdate=NOW()';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':name', $user, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password, PDO::PARAM_STR);
            $stmt->bindValue(':picture', $picture, PDO::PARAM_STR);
            $stmt->execute();
	        unset($_SESSION['join']);
	        header('Location: thanks.php');
	        exit();
        }catch (PDOException $e){
            echo($e->getMessage());
            die();
        }
    }
} else {
    if (!isset($_SESSION['join'])) {
        header('Location: input.php');
        exit();
    } else {
        $email = htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8');
        $user = htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES, 'UTF-8');
        $password = password_hash($_SESSION['join']['password'],PASSWORD_DEFAULT);
        $picture = htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES, 'UTF-8');
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>
	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会員登録</h1>
  </div>
  <div id="content">
		<form action="" method="POST">
			<input type="hidden" name="action" value="submit" />
		<dl>
		<dt>ニックネーム</dt>
		<dd>
			<?php echo $user; ?>
		</dd>
		<dt>メールアドレス</dt>
		<dd>
			<?php echo $email; ?>
		</dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>写真など</dt>
		<dd>
			<img src="./member_picture/<?php echo $picture; ?>" width="100" height="100" alt="" />
		</dd>
		</dl>
		<div><a href="input.php?action=rewrite">&laquo;&nbsp;修正する</a>　|　<input
		type="submit" value="登録" /></div>
		</form>
  </div>

</div>
</body>
</html>
