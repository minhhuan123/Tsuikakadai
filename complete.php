<?php
  session_start();
  require_once("functions.php");

  $edit = $_SESSION['edit'];
  $email = $_SESSION['email'];
  $user = $_SESSION['user'];
  $gender = $_SESSION['gender'];
  $locked = $_SESSION['locked'];
  $banned = $_SESSION['banned'];

$dbh = db_conn();      // データベース接続
try{
    $sql = "UPDATE members SET user = :user, gender = :gender, locked = :locked, banned = :banned, updatedate = NOW() WHERE id = :id";  //プレースホルダ
    $stmt = $dbh->prepare($sql);                           //クエリの実行準備
    $stmt->bindValue(':id', $edit, PDO::PARAM_INT);         //バインド:プレースホルダーを埋める
    $stmt->bindValue(':user', $user, PDO::PARAM_STR);      //バインド:プレースホルダ―の値を埋める
    $stmt->bindValue(':gender', $gender, PDO::PARAM_INT);  //バインド:プレースホルダーを埋める
    $stmt->bindValue(':locked', $locked, PDO::PARAM_STR);    //バインド:プレースホルダ―の値を埋める
    $stmt->bindValue(':banned', $banned, PDO::PARAM_STR);    //バインド:プレースホルダ―の値を埋める
    $stmt->execute();                                      //クエリの実行
    $dbh = null;                                           //MySQL接続解除
}catch (PDOException $e){
    echo($e->getMessage());
    die();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>更新結果画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
       <div>
            <h1>更新結果画面</h1>
       </div>
    </header>
</div>
<hr>
<p>メールアドレスは <?php echo $email;?> </p>
<p>ニックネームは <?php echo $user;?> さん</p>

<p>性別は <?php if( $gender === "1" ){ echo '男性'; }
		elseif( $gender === "2" ){ echo '女性'; }
		elseif( $gender === "9" ){ echo 'その他'; }
?> </p>
<p>ロック回数は <?php echo $locked;?> 回</p>
<p>使用禁止フラグは <?php echo $banned;?> </p>
<p>以上の内容で更新しました。</p>
<?php  /* セッション変数クリア */
   unset($_SESSION['condition_name']);
   unset($_SESSION['edit']);
   unset($_SESSION['user']);
   unset($_SESSION['email']);
   unset($_SESSION['gender']);
   unset($_SESSION['locked']);
   unset($_SESSION['banned']);
?>
<form action="index.html" method="POST">
<div class="button-wrapper">
	<button type="submit" class="btn btn--naby btn--shadow">TOPに戻る</button>
</div>
</form>
<hr>
<div class="container">
    <footer>
        <p>CCC.</p>
    </footer>
</div>
</body>
</html>
