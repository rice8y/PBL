<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mail form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">健康管理アプリ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="set_goals_form.php">目標設定</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">基本情報</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checklist.php">チェックリスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="mail_form.php">お問い合わせ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout_form.php">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <br>
        <h2 class="text-left">お問い合わせフォーム</h2><br>
    </div>
    <div class="container text-center">
        <form
            action="https://docs.google.com/forms/u/0/d/e/1FAIpQLSeI26a_AeGKJvJsr4ugiqIde_vMY2usB77ZzUkBCs3U5YLpgQ/formResponse"
            method="POST" target="hidden_iframe" onsubmit="submitted=true;" id="mail-form" novalidate>
            <div class="form-outline mb-4 text-start">
                <label class="form-label" for="inputName">お名前</label>
                <input type="text" name="entry.751100104" id="inputName" class="form-control form-control-lg"
                    required />
                <div class="invalid-feedback">お名前を入力してください</div>
            </div>
            <div class="form-outline mb-4 text-start">
                <label class="form-label" for="inputEmail">メールアドレス</label>
                <input type="email" id="inputEmail" class="form-control form-control-lg" name="entry.1359326191"
                    required />
                <div class="invalid-feedback">正しいメールアドレスを入力してください</div>
            </div>
            <div class="form-outline mb-4 text-start">
                <label class="form-label" for="inputMessage">お問い合わせ内容</label>
                <textarea id="inputMessage" class="form-control" name="entry.1086461285" rows="4" required></textarea>
                <div class="invalid-feedback">お問い合わせ内容を入力してください</div>
            </div>
            <button class="btn btn-primary btn-lg btn-block" type="submit">送信</button>
        </form>
    </div>
    <script>
        let submitted = false;
    </script>
    <iframe name="hidden_iframe" id="hidden_iframe" style="display: none"
        onload="if(submitted){window.location='thanks.php';}"></iframe>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
    <script>
        document.getElementById("mail-form").addEventListener("submit", function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add("was-validated");
        });
    </script>
</body>

</html>