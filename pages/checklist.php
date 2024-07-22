<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login_form.php');
    exit;
}

$db_file = "../sqlite3.db";
$tbl1 = "users";
$tbl2 = "check_lists";
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

try {
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
    $sqlite->enableExceptions(true);

    $stmt = $sqlite->prepare("SELECT list_id, item, state FROM $tbl2 WHERE user_id = :user_id ORDER BY list_id");
    $stmt->bindValue(':user_id', $user_id, SQLITE3_TEXT);
    $result = $stmt->execute();
    $items = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $items[] = $row;
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
    <title>checklist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-inline-custom {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .input-col {
            flex: 0 0 auto;
            width: 70%;
            margin-right: 1rem;
        }

        .button-col {
            flex: 0 0 auto;
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
                        <a class="nav-link active" aria-current="page" href="checklist.php">チェックリスト</a>
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
        <div class="d-flex justify-content-center">
            <form action="add_list.php" method="POST" id="add-list-form" class="form-inline-custom col-10" novalidate>
                <div class="input-col">
                    <input class="form-control" type="text" name="item" id="item" placeholder="タスクを入力" required>
                    <div class="invalid-feedback">タスクを入力して下さい</div>
                </div>
                <div class="button-col">
                    <button class="btn btn-primary btn-sm" type="submit" name="add">追加</button>
                </div>
            </form>
        </div>
        <br>
        <?php
        if (count($items) > 0) {
            echo "<table class='table'><thead><tr><th scope='col-1'></th><th scope='col-7'>Task</th><th scope='col-2'>Delete</th><tr></thead><tbody>";
            foreach ($items as $item) {
                $checked = $item['state'] == 1 ? 'checked' : '';
                $rowClass = $item['state'] == 1 ? 'table-success' : '';
                echo "<tr class='$rowClass'>";
                echo "<td class='col-1'><div class='form-check'><input class='form-check-input' type='checkbox' id='checkbox" . $item['list_id'] . "' $checked></div></td>";
                echo "<td class='col-7'>" . htmlspecialchars($item['item'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td class='col-2'><button class='btn btn-danger btn-sm' onclick='confirmDelete(" . $item['list_id'] . ")'><i class='bi bi-trash'></i></button></td>";
                echo "</tr>";
            }
            echo "</tbody><table>";
        }
        ?>
    </div>
    <script>
        <?php foreach ($items as $item): ?>
            const checkbox<?php echo $item['list_id']; ?> = document.getElementById('checkbox<?php echo $item['list_id']; ?>');
            checkbox<?php echo $item['list_id']; ?>.addEventListener('change', function () {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'change_state.php';
                const inputListId = document.createElement('input');
                inputListId.type = 'hidden';
                inputListId.name = 'list_id';
                inputListId.value = '<?php echo $item['list_id']; ?>';
                const inputState = document.createElement('input');
                inputState.type = 'hidden';
                inputState.name = 'state';
                inputState.value = checkbox<?php echo $item['list_id']; ?>.checked ? 1 : 0;
                form.appendChild(inputListId);
                form.appendChild(inputState);
                document.body.appendChild(form);
                form.submit();
            });
        <?php endforeach; ?>
        function confirmDelete(list_id) {
            if (window.confirm('削除しますか？')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'delete_list.php';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'list_id';
                input.value = list_id;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script>
        document.getElementById("add-list-form").addEventListener("submit", function (event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            this.classList.add("was-validated");
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
</body>

</html>