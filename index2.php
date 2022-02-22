<?php
session_start();
require('functions.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// ログインしている
	$_SESSION['time'] = time();
    $db = db_conn();
	$sql = 'SELECT * FROM members WHERE id= :id';
    $members = $db->prepare($sql);
    $members->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
    $members->execute();
    $member = $members->fetch();
} else {
	// ログインしていない
	header('Location: login.php');
	exit();
}

// 投稿を記録する
if (!empty($_POST)) {
	if ($_POST['message'] != '') {
		$sql = 'INSERT INTO posts SET member_id= :id, message= :message, reply_post_id= :reply_post_id, created=NOW()';
		$message = $db->prepare($sql);
    $message->bindValue(':id', $member['id'], PDO::PARAM_INT);
    $message->bindValue(':message', $_POST['message'], PDO::PARAM_STR);
    if($_POST['reply_post_id'] == '') {
       $message->bindValue(':reply_post_id', 0, PDO::PARAM_INT);  // 未指定の場合 0 を設定
    } else {
       $message->bindValue(':reply_post_id', $_POST['reply_post_id'], PDO::PARAM_INT);
    }
    $message->execute();
		header('Location: index2.php');
		exit();
	}
}

// 投稿を取得する
$page = $_GET['page'];
if ($page == '') {
	$page = 1;
}
$page = max($page, 1);

// 最終ページを取得する
$sql = 'SELECT COUNT(*) AS cnt FROM posts';
$counts = $db->prepare($sql);
$counts->execute();
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / PAGING);
$page = min($page, $maxPage);

$start = ($page - 1) * PAGING;
$start = max(0, $start);

$sql = 'SELECT m.user, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT :start, :list';
$posts = $db->prepare($sql);
$posts->bindValue(':start', $start, PDO::PARAM_INT);
$posts->bindValue(':list', PAGING, PDO::PARAM_INT);
$posts->execute();

// 返信の場合
if (isset($_GET['res'])) {
	$sql = 'SELECT m.user, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id= :res ORDER BY p.created DESC';
	$response = $db->prepare($sql);
    $response->bindValue(':res', $_GET['res'], PDO::PARAM_INT);
	$response->execute();

	$table = $response->fetch();
	$message = '@' . $table['user'] . ' ' . $table['message'];
}

// htmlspecialcharsのショートカット
function hsc($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// 本文内のURLにリンクを設定します
function makeLink($value) {
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)", '<a href="\1\2">\1\2</a>' , $value);
}
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
  	<div style="text-align: right"><a href="logout.php">ログアウト</a></div>
    <form action="" method="post">
      <dl>
        <dt><?php echo hsc($member['user']); ?>さん、メッセージをどうぞ</dt>
        <dd>
          <textarea name="message" cols="50" rows="5"><?php echo hsc($message); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php echo hsc($_GET['res']); ?>" />
        </dd>
      </dl>
      <div>
        <p>
          <input type="submit" value="投稿する" />
        </p>
      </div>
    </form>

<?php
foreach ($posts as $post):
?>
    <div class="msg">
    <img src="member_picture/<?php echo hsc($post['picture']); ?>" width="48" height="48" alt="<?php echo hsc($post['user']); ?>" />
    <p><?php echo makeLink(hsc($post['message'])); ?><span class="name">（<?php echo hsc($post['user']); ?>）</span>[<a href="index2.php?res=<?php echo hsc($post['id']); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?php echo hsc($post['id']); ?>"><?php echo hsc($post['created']); ?></a>
    <?php
    if ($post['reply_post_id'] > 0):
    ?>
    <a href="view.php?id=<?php echo
    hsc($post['reply_post_id']); ?>">
    返信元のメッセージ</a>
    <?php
    endif;
    ?>
    <?php
    if ($_SESSION['id'] == $post['member_id']):
    ?>
    [<a href="delete.php?id=<?php echo hsc($post['id']); ?>"
    style="color: #F33;">削除</a>]
    <?php
    endif;
    ?>
    </p>
    </div>
<?php
endforeach;
?>

<ul class="paging">
<?php
if ($page > 1) {
?>
<li><a href="index2.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
<?php
} else {
?>
<li>前のページへ</li>
<?php
}
?>
<?php
if ($page < $maxPage) {
?>
<li><a href="index2.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
<?php
} else {
?>
<li>次のページへ</li>
<?php
}
?>
</ul>
  </div>
</div>
</body>
</html>
