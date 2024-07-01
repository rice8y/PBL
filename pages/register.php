<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $db_file = "sqlite3.db";
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READWRITE);
        $sqlite->enableExceptions(true);

        $user_table = "user_" . SQLite3::escapeString($username);
        $create_user_table_query = "CREATE TABLE IF NOT EXISTS $user_table (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            steps INTEGER NOT NULL,
            sleep TEXT NOT NULL,
            time DATETIME NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime'))
        )";
        $sqlite->exec($create_user_table_query);

        $stmt = $sqlite->prepare("INSERT INTO userinfo (username, password) VALUES (:username, :password)");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $stmt->bindValue(':password', $hashed_password, SQLITE3_TEXT);
        $stmt->execute();

        $_SESSION['username'] = $username;

        header('Location: home.php');
        $sqlite->close();
    } catch (Exception $e) {
        // if ($e->getCode() == 0) {
        //     $_SESSION['error'] = "ユーザID: " . $username . " は既に存在します";
        //     header('Location: register_form.php');
        // } else {
        //     echo "Caught exception: " . $e->getMessage();
        // }
    }
}
?>