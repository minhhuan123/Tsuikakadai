<?php
session_start();
require('functions.php');

if ($_COOKIE['email'] != '') {
$_POST['email'] = $_COOKIE['email'];
$_POST['password'] = $_COOKIE['password'];
$_POST['save'] = 'on';
}

if (!empty($_POST)) {
	// ログインの処理
	if ($_POST['email'] != '' && $_POST['password'] != '') {
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
        $dbh = db_conn();
        try{
            $sql = 'SELECT * FROM operations WHERE email=:email';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
			$member = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if( password_verify($password, $member['password']) ) {
				// ログイン成功
				session_regenerate_id(true); // 現在のセッションIDを新しく生成したものと置き換える
				                             // セッションハイジャック対策
				$_SESSION['id'] = $member['id'];
				$_SESSION['time'] = time();

				// ログイン情報を記録する
				if ($_POST['save'] == 'on') {
				    setcookie('email', $_POST['email'], time()+60*60*24*14);
				    setcookie('password', $_POST['password'], time()+60*60*24*14);
				}
				header('Location: ope.html');
				exit();
            }else{
				// ログイン認証失敗
				$error['login'] = 'failed';
            } 
        }catch (PDOException $e){
            echo($e->getMessage());
            die();
        }
	} else {
		$error['login'] = 'blank';
	}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>管理機能</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>ログイン画面</h1>
		</div>
		<div id="content">
			<div id="lead">
				<p>メールアドレスとパスワードを入力してログインしてください。</p>
				<p>会員登録がまだの方はこちらからどうぞ。</p>
			</div>
			<form action="" method="POST">
				<dl>
					<dt>メールアドレス</dt>
					<dd>
						<input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"/>
						<?php if ($error['login'] == 'blank'): ?>
							<p class="error">* メールアドレスとパスワードをご記入ください</p>
						<?php endif; ?>
						<?php if ($error['login'] == 'failed'): ?>
							<p class="error">* ユーザーIDあるいはパスワードに誤りがあります。正しく入力ください。</p>
						<?php endif; ?>
					</dd>
					<dt>パスワード</dt>
					<dd>
						<input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
					</dd>
					<dt>ログイン情報の記録</dt>
					<dd>
						<input id="save" type="checkbox" name="save" value="on"><label
						for="save">次回からは自動的にログインする</label>
					</dd>
				</dl>
				<div><input type="submit" value="ログインする" /></div>
			</form>
		</div>

	</div>
</body>
</html>
