<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
    exit;
}

$db_file = "sqlite3.db";
$tbl = "users";
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

try {
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
    $sqlite->enableExceptions(true);

    $stmt = $sqlite->prepare("SELECT nickname, height, weight, date_of_birth, gender FROM $tbl WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $nickname = $row ? $row['nickname'] : null;
    $height = $row ? $row['height'] : null;
    $weight = $row ? $row['weight'] : null;
    $birth = $row ? $row['date_of_birth'] : null;
    $gender = $row ? $row['gender'] : null;
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
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
                        <a class="nav-link active" aria-current="page" href="profile.php">基本情報</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checklist.php">チェックリスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mail_form.php">お問い合わせ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout_form.php">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div>
                <br>
                <form action="set_profile.php" method="POST" id="set-profile-form">
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="nickname">ニックネーム</label>
                        <input type="text" name="nickname" id="nickname" class="form-control form-control-lg"
                            placeholder="<?php echo $nickname; ?>" />
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="birth">誕生日</label>
                        <input type="date" name="birth" id="birth" class="form-control form-control-lg"
                            value="<?php echo $birth; ?>" onfocus="removePlaceholder()" onblur="addPlaceholder()" />
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="gender">性別</label>
                        <select name="gender" id="gender" class="form-control form-control-lg">
                            <option value="" disabled <?php echo $gender === null ? 'selected' : ''; ?>>選択してください</option>
                            <option value="male" <?php echo $gender === 'male' ? 'selected' : ''; ?>>男性</option>
                            <option value="female" <?php echo $gender === 'female' ? 'selected' : ''; ?>>女性</option>
                            <option value="other" <?php echo $gender === 'other' ? 'selected' : ''; ?>>その他</option>
                        </select>
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="height">身長 [cm]</label>
                        <input type="number" min="0" step="0.01" name="height" id="height"
                            class="form-control form-control-lg" placeholder="<?php echo $height; ?>" />
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="weight">体重 [kg]</label>
                        <input type="number" min="0" step="0.01" name="weight" id="weight"
                            class="form-control form-control-lg" placeholder="<?php echo $weight; ?>" />
                    </div>
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block"
                        type="submit" name="set">更新</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        function addPlaceholder() {
            const input = document.getElementById('birth');
            if (input.value === '') {
                input.type = 'text';
                input.value = '<?php echo $birth; ?>';
                input.style.color = 'gray';
            }
        }

        function removePlaceholder() {
            const input = document.getElementById('birth');
            if (input.value === '<?php echo $birth; ?>') {
                input.value = '';
                input.type = 'date';
                input.style.color = 'black';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('birth');
            if (input.value === '<?php echo $birth; ?>' || input.value === '') {
                input.value = '<?php echo $birth; ?>';
                input.style.color = 'gray';
                input.type = 'text';
            }
        });

        $(function () {
            $('#gender').on('change', function () {
                $(this).find('option').css('color', 'black');
                $(this).find('option:selected').css('color', 'gray');
            }).trigger('change');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>