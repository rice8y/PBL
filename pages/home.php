<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
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

    $stmt = $sqlite->prepare("SELECT sleep FROM $user_table ORDER BY time DESC LIMIT 1");
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);
    $sleep_data = [];
    if ($row) {
        $sleep_data[] = $row;
    }

    $stmt = $sqlite->prepare("SELECT target_steps FROM $user_table ORDER BY time DESC LIMIT 1");
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $tsteps_data = [];
    if ($row) {
        $tsteps_data[] = $row;
        $json_steps = json_encode($tsteps_data);
        $json_steps = json_decode($json_steps, true);
        $target_steps = $json_steps[0]['target_steps'];
    } else {
        $target_steps = 9999;
    }

    $stmt = $sqlite->prepare("SELECT target_sleep FROM $user_table ORDER BY time DESC LIMIT 1");
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $tsleep_data = [];
    if ($row) {
        $tsleep_data[] = $row;
        $json_sleep = json_encode($tsleep_data);
        $json_sleep = json_decode($json_sleep, true);
        $target_sleep = $json_sleep[0]['target_sleep'];
    } else {
        $target_sleep = "24.00";
    }

    $stmt = $sqlite->prepare("SELECT target_score FROM $user_table ORDER BY time DESC LIMIT 1");
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $tscore_data = [];
    if ($row) {
        $tscore_data[] = $row;
        $json_score = json_encode($tscore_data);
        $json_score = json_decode($json_score, true);
        $target_score = $json_score[0]['target_score'];
    } else {
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
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.plot.ly/plotly-2.32.0.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="circle.css">
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
                        <a class="nav-link" href="set_goals_form.php">目標設定</a>
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
                </ul>
            </div>
        </div>
    </nav>
    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div>
                <br>
                <form action="set_data.php" method="POST" id="steps-form" novalidate>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="steps">歩数</label>
                        <input type="number" name="steps" id="steps" class="form-control form-control-lg" required />
                        <div class="invalid-feedback">歩数を入力して下さい</div>
                    </div>
                    <div class="form-outline mb-4 text-start">
                        <label class="form-label" for="sleep">睡眠時間</label>
                        <input type="time" name="sleep" id="sleep" class="form-control form-control-lg" required />
                        <div class="invalid-feedback">睡眠時間を入力して下さい</div>
                    </div>
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg btn-block"
                        type="submit" name="set">更新</button>
                </form>
                <br>
            </div>
            <div class="col-sm-9" id="plot"></div>
            <div class="col-sm-3">
                <div id="pie-chart" class="content">
                    <div class="pie-chart-wrap">
                        <div class="box blue" data-percent="0">
                            <h3>睡眠時間</h3>
                            <div class="percent">
                                <svg>
                                    <circle class="base" cx="75" cy="75" r="70"></circle>
                                    <circle class="line" cx="75" cy="75" r="70"></circle>
                                </svg>
                                <div class="number">
                                    <h3 class="title"><span class="value">0</span><span>h</span></h3>
                                </div>
                            </div>
                        </div>
                        <div class="box red" data-percent="0">
                            <h3>歩数</h3>
                            <div class="percent">
                                <svg>
                                    <circle class="base" cx="75" cy="75" r="70"></circle>
                                    <circle class="line" cx="75" cy="75" r="70"></circle>
                                </svg>
                                <div class="number">
                                    <h3 class="title"><span class="value">0</span><span>steps</span></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const myDiv = document.getElementById('plot');
        var stepsData = <?php echo json_encode($steps_data); ?>;
        var sleepData = <?php echo json_encode($sleep_data); ?>;

        var xData = stepsData.map(item => new Date(item.time));
        var yData = stepsData.map(item => item.steps);

        var formattedXData = xData.map(date => {
            const options = { month: 'short', day: 'numeric', weekday: 'short' };
            return date.toLocaleDateString('ja-JP', options);
        });

        var data = [
            {
                x: formattedXData,
                y: yData,
                type: 'scatter'
            }
        ];

        var layout = {
            yaxis: {
                rangemode: 'tozero'
            },
            responsive: true
        };

        Plotly.newPlot(myDiv, data, layout);

        window.addEventListener('resize', () => {
            Plotly.Plots.resize(myDiv);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const boxes = document.querySelectorAll('.box');


            function updateCircle(box, percent) {
                const circle = box.querySelector('.line');
                const radius = circle.r.baseVal.value;
                const circumference = 2 * Math.PI * radius;
                const offset = circumference - (percent / 100 * circumference);
                circle.style.strokeDashoffset = offset;
            }

            boxes.forEach(box => {
                const valueSpan = box.querySelector('.value');
                const dataPercent = box.getAttribute('data-percent');
                valueSpan.textContent = dataPercent;

                updateCircle(box, dataPercent);
            });

            if (sleepData.length > 0) {
                const sleepValue = sleepData[0].sleep;
                const [hours, minutes] = sleepValue.split(':').map(Number);
                const total = hours + minutes / 60;
                const sleepBox = document.querySelector('.box.blue .value');
                sleepBox.textContent = total.toFixed(2);

                const sleepPercent = (total / parseFloat("<?php echo $target_sleep; ?>")) * 100;
                document.querySelector('.box.blue').setAttribute('data-percent', sleepPercent.toFixed(2));
                updateCircle(document.querySelector('.box.blue'), sleepPercent.toFixed(2));
                console.log(sleepPercent);
            }

            if (stepsData.length > 0) {
                const latestSteps = stepsData[stepsData.length - 1].steps;
                const stepsBox = document.querySelector('.box.red .value');
                stepsBox.textContent = latestSteps;

                const stepsPercent = (latestSteps / parseInt("<?php echo $target_steps; ?>")) * 100;
                document.querySelector('.box.red').setAttribute('data-percent', stepsPercent.toFixed(2));
                updateCircle(document.querySelector('.box.red'), stepsPercent.toFixed(2));
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>