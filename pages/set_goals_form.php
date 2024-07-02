<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
    exit;
}

$db_file = "sqlite3.db";
$tbl = "health_records";
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

try {
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
    $sqlite->enableExceptions(true);

    $stmt = $sqlite->prepare("SELECT target_steps FROM $tbl WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $steps_data = [];
    if ($row) {
        $steps_data[] = $row;
        $json_steps = json_encode($steps_data);
        $json_steps = json_decode($json_steps, true);
        $target_steps = $json_steps[0]['target_steps'];
    }else{
        $target_steps = 9999;
    }

    $stmt = $sqlite->prepare("SELECT target_sleep_time FROM $tbl WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $sleep_data = [];
    if ($row) {
        $sleep_data[] = $row;
        $json_sleep = json_encode($sleep_data);
        $json_sleep = json_decode($json_sleep, true);
        $target_sleep = $json_sleep[0]['target_sleep_time'];
    }else{
        $target_sleep = "24:00";
    }

    $stmt = $sqlite->prepare("SELECT target_score FROM $tbl WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $score_data = [];
    if ($row) {
        $score_data[] = $row;
        $json_score = json_encode($score_data);
        $json_score = json_decode($json_score, true);
        $target_score = $json_score[0]['target_score'];
    }else{
        $target_score = 100;
    }
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>set goals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
        input::placeholder {
            color: gray;
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
                        <a class="nav-link active" aria-current="page" href="set_goals_form.php">目標設定</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">基本情報</a>
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
                <form action="set_goals.php" method="POST" id="set-goals-form" novalidate>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="steps">歩数</label>
                        <input type="number" min="0" name="steps" id="steps" class="form-control form-control-lg"
                            placeholder="<?php echo $target_steps; ?>" required />
                        <div class="invalid-feedback">歩数を入力して下さい</div>
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="sleep">睡眠時間</label>
                        <input type="text" name="sleep" id="sleep" class="form-control form-control-lg"
                            value="<?php echo $target_sleep; ?>" onfocus="removePlaceholder()" onblur="addPlaceholder()" required />
                        <div class="invalid-feedback">睡眠時間を入力して下さい</div>
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="score">目標スコア</label>
                        <input type="number" min="0" max="100" name="score" id="score"
                            class="form-control form-control-lg" placeholder="<?php echo $target_score; ?>" required />
                        <div class="invalid-feedback">目標スコアを入力して下さい</div>
                    </div>
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block"
                        type="submit" name="set">更新</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function addPlaceholder() {
            const input = document.getElementById('sleep');
            if (input.value === '') {
                input.type = 'text';
                input.value = '<?php echo $target_sleep; ?>';
                input.style.color = 'gray';
            }
        }

        function removePlaceholder() {
            const input = document.getElementById('sleep');
            if (input.value === '<?php echo $target_sleep; ?>') {
                input.value = '';
                input.type = 'time';
                input.style.color = 'black';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('sleep');
            if (input.value === '<?php echo $target_sleep; ?>' || input.value === '') {
                input.value = '<?php echo $target_sleep; ?>';
                input.style.color = 'gray';
                input.type = 'text';
            }
        });

        document.getElementById("set-goals-form").addEventListener("submit", function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add("was-validated");
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>
