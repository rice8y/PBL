<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $db_file = "../sqlite3.db";
    $tbl_name = "users";
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READONLY);
        $sqlite->enableExceptions(true);

        $stmt = $sqlite->prepare("SELECT password FROM " . $tbl_name . " WHERE username = :username");
        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row && password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header('Location: home.php');
            exit;
        } else {
            $_SESSION['error'] = "ユーザIDまたはパスワードが違います";
            header('Location: login_form.php');
        }

        $sqlite->close();
    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
        exit;
    } finally {
        if (isset($sqlite)) {
            $sqlite->close();
        }
    }
}
?>