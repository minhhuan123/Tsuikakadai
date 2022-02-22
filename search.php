<!DOCTYPE html>
<html lang="ja">
<head>
    <title>ユーザ検索画面</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                    <h1>ユーザ検索画面</h1>
            </div>
        </header>
    </div>

    <hr>

<p>名前の一致するユーザーを検索し一覧で表示します</p>

    <div class="container">
        <form action="list.php" method="POST" class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="form-group">
                    <label for="email"><span >検索キーワード</span> </label>
                    <input type="text" id="email" name="email" class="form-control" value="" placeholder="例：aaa@bbb.ccc" autofocus>
                </div>

                <div class="button-wrapper">
                    <button type="button" onclick="location.href='index.html'">戻る</button>
                    <button type="submit" class="btn btn--naby btn--shadow">検索する</button>
                </div>
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
