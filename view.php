<?php
session_start();
require('functions.php');

if($_SERVER['REQUEST_METHOD'] === 'GET'){
	if (empty($_GET['id'])) {
		header('Location: index2.php');
		exit();
	} else {
		$id = $_GET['id'];
	}
}

// 投稿を取得する
$db = db_conn();
$sql = 'SELECT m.user, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=:id ORDER BY p.created DESC';
$posts = $db->prepare($sql);
$posts->bindValue(':id', $id, PDO::PARAM_STR);
$posts->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Simple掲示板</title>
	<link rel="stylesheet" href="style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>Simple掲示板</h1>
		</div>
		<div id="content">
			<p>&laquo;<a href="index2.php">一覧にもどる</a></p>

			<?php
			if ($post = $posts->fetch()):
				?>
				<div class="msg">
					<img src="member_picture/<?php echo htmlspecialchars($post['picture'], ENT_QUOTES); ?>" width="48" height="48" alt="<?php echo 	 htmlspecialchars($post['user'], ENT_QUOTES); ?>" />
					<p><?php echo htmlspecialchars($post['message'], ENT_QUOTES);
					?><span class="name">（<?php echo htmlspecialchars($post['user'], ENT_QUOTES); ?>）</span></p>
					<p class="day"><?php echo htmlspecialchars($post['created'], ENT_QUOTES); ?></p>
				</div>
				<?php
			else:
				?>
				<p>その投稿は削除されたか、URLが間違っています</p>
				<?php
			endif;
			?>
		</div>
	</div>
</body>
</html>
