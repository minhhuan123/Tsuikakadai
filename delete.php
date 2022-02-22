<?php
session_start();
require('functions.php');

if (isset($_SESSION['id'])) {
	$id = $_GET['id'];
	
	// 投稿をチェック
    $dbh = db_conn();
    $sql = 'SELECT * FROM posts WHERE id=:id';
    $messages = $dbh->prepare($sql);
    $messages->bindValue(':id', $id, PDO::PARAM_INT);
    $messages->execute();
	$message = $messages->fetch();

	if ($message['member_id'] == $_SESSION['id']) {
		// 削除する
        $sql = 'DELETE FROM posts WHERE id=:id';
        $del = $dbh->prepare($sql);
        $del->bindValue(':id', $id, PDO::PARAM_INT);
        $del->execute();
	}
}

header('Location: index2.php');
exit();
?>
