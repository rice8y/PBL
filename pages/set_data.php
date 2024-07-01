<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $db_file = "sqlite3.db";
    $username = $_SESSION['username'];
    $steps = $_POST['steps'];
    $sleep = $_POST['sleep'];

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        $user_table = "user_" . SQLite3::escapeString($username);

        $current_date = date('Y-m-d');

        $stmt = $sqlite->prepare("SELECT COUNT(*) as count FROM $user_table WHERE date(time) = :current_date");
        $stmt->bindValue(':current_date', $current_date, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row['count'] > 0) {
            $stmt = $sqlite->prepare("UPDATE $user_table SET steps = :steps, sleep = :sleep WHERE date(time) = :current_date");
            $stmt->bindValue(':steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':sleep', $sleep, SQLITE3_TEXT);
            $stmt->bindValue(':current_date', $current_date, SQLITE3_TEXT);
            $stmt->execute();
        } else {
            $stmt = $sqlite->prepare("INSERT INTO $user_table (steps, sleep) VALUES (:steps, :sleep)");
            $stmt->bindValue(':steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':sleep', $sleep, SQLITE3_TEXT);
            $stmt->execute();
        }

        header('Location: home.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>