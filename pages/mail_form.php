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
    <style>
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
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
        <form id="mail-form" novalidate>
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
            <button class="btn btn-primary btn-lg btn-block" type="button" data-bs-toggle="modal"
                data-bs-target="#confirmationModal" onclick="showConfirmation()">確認</button>
        </form>
    </div>
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">入力内容の確認</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>お名前:</strong> <span id="confirmName"></span></p>
                    <p><strong>メールアドレス:</strong> <span id="confirmEmail"></span></p>
                    <p><strong>お問い合わせ内容:</strong></p>
                    <div style="white-space: pre-wrap;"><span id="confirmMessage"></span></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">修正</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">送信</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showConfirmation() {
            if (!document.getElementById("mail-form").checkValidity()) {
                document.getElementById("mail-form").classList.add("was-validated");
                return;
            }
            document.getElementById("confirmName").textContent = document.getElementById("inputName").value;
            document.getElementById("confirmEmail").textContent = document.getElementById("inputEmail").value;
            document.getElementById("confirmMessage").textContent = document.getElementById("inputMessage").value;
        }

        function submitForm() {
            let form = document.getElementById("mail-form");
            let iframe = document.getElementById("hidden_iframe");
            form.action = "https://docs.google.com/forms/u/0/d/e/1FAIpQLSeI26a_AeGKJvJsr4ugiqIde_vMY2usB77ZzUkBCs3U5YLpgQ/formResponse";
            form.method = "POST";
            form.target = "hidden_iframe";
            submitted = true;
            form.submit();
        }

        let submitted = false;
    </script>
    <iframe name="hidden_iframe" id="hidden_iframe" style="display: none"
        onload="if(submitted){window.location='thanks.php';}"></iframe>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>