<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
    exit;
}

$db_file = "sqlite3.db";
$tbl1 = "users";
$tbl2 = "health_records";
$username = $_SESSION['username'];

try {
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
    $sqlite->enableExceptions(true);

    $stmt = $sqlite->prepare("SELECT user_id, height, weight FROM $tbl1 WHERE username = :username");
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $user_id = $row ? $row['user_id'] : null;
    $_SESSION['user_id'] = $user_id;
    $height = $row ? $row['height'] : null;
    $weight = $row ? $row['weight'] : null;

    $current_date = date('Y-m-d');
    $date_six_days_ago = date('Y-m-d', strtotime('-6 days', strtotime($current_date)));

    $stmt = $sqlite->prepare("SELECT steps, date FROM $tbl2 WHERE user_id = :user_id AND date BETWEEN :start_date AND :end_date ORDER BY date ASC");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':start_date', $date_six_days_ago);
    $stmt->bindValue(':end_date', $current_date);
    $result = $stmt->execute();

    $steps_data = [];
    for ($date = strtotime($date_six_days_ago); $date <= strtotime($current_date); $date = strtotime('+1 day', $date)) {
        $formatted_date = date('Y-m-d', $date);
        $steps_data[$formatted_date] = 0;
    }
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $steps_data[$row['date']] = $row['steps'];
    }

    $stmt = $sqlite->prepare("SELECT sleep_time FROM $tbl2 WHERE user_id = :user_id AND date = :current_date");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue("current_date", $current_date);
    $result = $stmt->execute();

    $row = $result->fetchArray(SQLITE3_ASSOC);
    $sleep_data = [];
    if ($row) {
        $sleep_data = $row;
    } else {
        $sleep_data = ['sleep_time' => 0];
    }

    $stmt = $sqlite->prepare("SELECT target_steps FROM $tbl2 WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
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

    $stmt = $sqlite->prepare("SELECT target_sleep_time FROM $tbl2 WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $tsleep_data = [];
    if ($row) {
        $tsleep_data[] = $row;
        $json_sleep = json_encode($tsleep_data);
        $json_sleep = json_decode($json_sleep, true);
        $target_sleep = $json_sleep[0]['target_sleep_time'];
    } else {
        $target_sleep = "24.00";
    }

    $stmt = $sqlite->prepare("SELECT target_score FROM $tbl2 WHERE user_id = :user_id ORDER BY date DESC LIMIT 1");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
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

function bmi($h, $w)
{
    return number_format($w / pow($h / 100, 2), 2);
}
function aw($h)
{
    return pow($h / 100, 2) * 22;
}
function diff($h, $w)
{
    return number_format($w - aw($h), 2);
}
function eval_bmi($bmi)
{
    if (0 <= $bmi && $bmi < 18.5) {
        return "低体重(痩せ型)";
    } else if ($bmi < 25) {
        return "普通体重";
    } else if ($bmi < 30) {
        return "肥満(1度)";
    } else if ($bmi < 35) {
        return "肥満(2度)";
    } else if ($bmi < 40) {
        return "肥満(3度)";
    } else {
        return "肥満(4度)";
    }
}

$diff_value = diff($height, $weight);
if ($diff_value > 0) {
    $diff_class = "text-danger";
} else if ($diff_value < 0) {
    $diff_class = "text-primary";
} else {
    $diff_class = "text-success";
}
if ($diff_class === "text-danger") {
    $diff_value = '+' . $diff_value;
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
                        <a class="nav-link" href="mail_form.php">お問い合わせ</a>
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
        <h2 class="text-left">活動記録</h2><br>
    </div>
    <div class="container text-center">
        <div class="row justify-content-md-center">
            <div class="col-sm-5">
                <br><br>
                <form action="set_data.php" method="POST" id="set-data-form" novalidate>
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
            <div class="col-sm-7">
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
            <div class="col-sm-12" id="plot"></div>
        </div>
    </div>
    <div class="container">
        <br>
        <h2 class="text-left">身体状況</h2><br>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">BMI</th>
                    <th scope="col">適正体重 [kg]</th>
                    <th scope="col">適正体重との比較 [kg]</th>
                    <th scope="col">評価</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo bmi($height, $weight); ?></td>
                    <td><?php echo number_format(aw($height), 2); ?></td>
                    <td><span class="<?php echo $diff_class; ?>"><?php echo $diff_value; ?></span></td>
                    <td><?php echo eval_bmi(bmi($height, $weight)); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        const myDiv = document.getElementById('plot');
        var stepsData = <?php echo json_encode($steps_data); ?>;
        var sleepData = <?php echo json_encode($sleep_data); ?>;
        const sleepTime = Object.values(sleepData);

        const xData = Object.keys(stepsData);
        const yData = Object.values(stepsData);

        const formattedXData = xData.map(date => {
            const options = { month: 'short', day: 'numeric', weekday: 'short' };
            const parsedDate = new Date(date);
            return parsedDate.toLocaleDateString('ja-JP', options);
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
                let offset = circumference - (percent / 100 * circumference);

                if (percent > 100) {
                    percent = 100;
                    offset = 0;
                    box.classList.add('over-100');
                } else {
                    box.classList.remove('over-100');
                }

                circle.style.strokeDashoffset = offset;
            }

            boxes.forEach(box => {
                const valueSpan = box.querySelector('.value');
                const dataPercent = box.getAttribute('data-percent');
                valueSpan.textContent = dataPercent;

                updateCircle(box, dataPercent);
            });

            if (sleepTime.length > 0) {
                const sleepValue = sleepTime[0];
                const [hours, minutes] = sleepValue.split(':').map(Number);
                const total = hours + minutes / 60;
                const sleepBox = document.querySelector('.box.blue .value');
                sleepBox.textContent = total.toFixed(2);

                const sleepPercent = (total / parseFloat("<?php echo $target_sleep; ?>")) * 100;
                document.querySelector('.box.blue').setAttribute('data-percent', sleepPercent.toFixed(2));
                updateCircle(document.querySelector('.box.blue'), sleepPercent.toFixed(2));
            }

            if (yData.length > 0) {
                const latestSteps = yData[6];
                const stepsBox = document.querySelector('.box.red .value');
                stepsBox.textContent = latestSteps;

                const stepsPercent = (latestSteps / parseInt("<?php echo $target_steps; ?>")) * 100;
                document.querySelector('.box.red').setAttribute('data-percent', stepsPercent.toFixed(2));
                updateCircle(document.querySelector('.box.red'), stepsPercent.toFixed(2));
            }
        });

        document.getElementById("set-data-form").addEventListener("submit", function (event) {
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