<?php session_start() ?>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <section class="vh-100" style="background-color: white;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <h3 class="mb-5">新規登録</h3>
                            <form action="register.php" method="POST" id="register-form" class="needs-validation"
                                novalidate>
                                <div class="form-outline mb-4 text-start">
                                    <label class="form-label" for="typeUserID">ユーザID</label>
                                    <input type="text" name="username" id="typeUserID"
                                        class="form-control form-control-lg" required />
                                    <div class="invalid-feedback">ユーザIDを入力して下さい</div>
                                </div>
                                <div class="form-outline mb-4 text-start">
                                    <label class="form-label" for="typePassword">パスワード</label>
                                    <input type="password" name="password" id="typePassword"
                                        class="form-control form-control-lg" required oninput="checkPassword()" />
                                    <div class="invalid-feedback">パスワードを入力して下さい</div>
                                </div>
                                <div class="form-outline mb-4 text-start">
                                    <label class="form-label" for="typeConfirmPassword">確認用パスワード</label>
                                    <input type="password" id="typeConfirmPassword" class="form-control form-control-lg"
                                        required oninput="checkPassword()" />
                                    <div class="invalid-feedback" id="comfirmPasswordInvalidFeedback">確認用パスワードを入力して下さい
                                    </div>
                                </div>
                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3">入力を保持する</label>
                                </div>
                                <div class="mb-4 text-start">
                                    <a href="login_form.php">ログイン</a>
                                </div>
                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="text-danger mb-4">
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <button class="btn btn-primary btn-lg btn-block" type="submit"
                                    name="register">登録</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.getElementById("register-form").addEventListener("submit", function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add("was-validated");
        });
        function checkPassword() {
            var password = document.getElementById("typePassword").value;
            var confirmPassword = document.getElementById("typeConfirmPassword").value;

            if (password !== confirmPassword) {
                document.getElementById("typeConfirmPassword").setCustomValidity("パスワードが一致しません");
                document.getElementById("confirmPasswordInvalidFeedback").innerHTML = "パスワードが一致しません";
            } else {
                document.getElementById("typeConfirmPassword").setCustomValidity("");
            }
        }
    </script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>