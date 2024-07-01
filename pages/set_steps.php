<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $db_file = "sqlite3.db";
    $username = $_SESSION['username'];
    $steps = $_POST['steps'];

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        $user_table = "user_" . SQLite3::escapeString($username);

        $stmt = $sqlite->prepare("INSERT INTO $user_table (steps) VALUES (:steps)");
        $stmt->bindValue(':steps', $steps, SQLITE3_INTEGER);
        $stmt->execute();

        header('Location: home.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>