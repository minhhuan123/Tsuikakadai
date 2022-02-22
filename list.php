<?php
session_start();
require_once("functions.php");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST["email"])){
        if(! empty($_POST["email"])) {
           $email = htmlspecialchars($_POST["email"], ENT_QUOTES, 'UTF-8');
           $_SESSION["condition_name"] = $_POST["email"];
        } else {
            $_SESSION["condition_name"] = $_POST["email"];
        }
    }
} else {
    $email = $_SESSION["condition_name"];  //from edit.php
}

$dbh = db_conn();

$data = [];

try{
    $sql = "SELECT * FROM members WHERE email like :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':email', '%'.$email.'%', PDO::PARAM_STR);
    $stmt->execute();
    $count = 0;
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
        $count++;
    }

}catch (PDOException $e){
    echo($e->getMessage());
    die();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>検索結果一覧画面</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header>
       <div>
            <h1>検索結果一覧画面</h1>
       </div>
    </header>
</div>
<hr>
<p>データ件数：<?php echo $count;?>件</p>

<div class="container">
<form action="edit.php" method="POST">

<table border=1>
    <tr><th>id</th><th>メールアドレス</th><th>ニックネーム</th><th>ロック回数</th><th>性別</th><th>選択対象</th></tr>
    <?php foreach($data as $row): ?>
    <tr>
    <td><?php echo $row['id'];?></td>
    <td><?php echo $row['email'];?></td>
    <td><?php echo $row['user'];?></td>
    <td><?php echo $row['locked'];?></td>
    <td>
        <?php
           if ($row['gender'] === 1) {
              echo "男性";
           } elseif ($row['gender'] === 2) {
              echo "女性";
           } else {
              echo "その他";
           }
        ?>
    </td>
    <td>
        <input type="radio" name="edit" id="edit" value="<?php echo $row[id];?>" ><label for="edit">更新</label>
    </td>
    </tr>
    <?php endforeach; ?>
</table>
<p style="margin:8px;">

<p>更新するデータを選択してください</p>

        <div class="button-wrapper">
            <button type="button" onclick="location.href='search_u.php'">戻る</button>
	        <button type="submit" class="btn btn--naby btn--shadow">更新する</button>
        </div>
</form>
</div>

<hr>
<div class="container">
    <footer>
        <p>CCC.</p>
    </footer>
</div>

</body>
</html>
