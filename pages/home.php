<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$db_file = "sqlite3.db";
$username = $_SESSION['username'];

try {
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
    $sqlite->enableExceptions(true);

    $user_table = "user_" . SQLite3::escapeString($username);
    $stmt = $sqlite->prepare("SELECT steps, time FROM $user_table ORDER BY time DESC LIMIT 7");
    $result = $stmt->execute();

    $steps_data = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $steps_data[] = $row;
    }

    $steps_data = array_reverse($steps_data);

} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.plot.ly/plotly-2.32.0.min.js" charset="utf-8"></script>
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
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="set_goals.php">目標設定</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">基本情報</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="checklist.php">チェックリスト</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout_form.php">ログアウト</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li> -->
                </ul>
            </div>
        </div>
    </nav>
    <div class="container text-center">
        <div id="plot"></div>
        <div class="row justify-content-md-center">
            <div class="col-md-auto">
                <!-- <div id="plot"></div> -->
                <div>
                    <form action="set_steps.php" method="POST" id="steps-form" novalidate>
                        <div class="form-outline mb-4 text-start">
                            <label class="form-label" for="steps">歩数</label>
                            <input type="number" name="steps" id="steps" class="form-control form-control-lg"
                                required />
                            <div class="invalid-feedback">歩数を入力して下さい</div>
                        </div>
                        <div class="form-outline mb-4 text-start">
                            <label class="form-label" for="sleep">睡眠時間</label>
                            <input type="time" name="sleep" id="sleep" class="form-control form-control-lg"
                                required />
                            <div class="invalid-feedback">睡眠時間を入力して下さい</div>
                        </div>
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block"
                            type="submit" name="set">更新</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const myDiv = document.getElementById('plot');
        var stepsData = <?php echo json_encode($steps_data); ?>;

        var xData = stepsData.map(item => item.time);
        var yData = stepsData.map(item => item.steps);

        var data = [
            {
                x: xData,
                y: yData,
                type: 'scatter'
            }
        ];

        Plotly.newPlot(myDiv, data);

    </script>
    <!-- contents -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>