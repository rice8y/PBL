<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $db_file = "../sqlite3.db";
    $tbl = "health_records";
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $steps = $_POST['steps'];
    $sleep = $_POST['sleep'];
    $score = $_POST['score'];

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        $current_date = date('Y-m-d');

        $stmt = $sqlite->prepare("SELECT COUNT(*) as count FROM $tbl WHERE user_id = :user_id AND date = :current_date");
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':current_date', $current_date, SQLITE3_TEXT);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row['count'] > 0) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET target_steps = :target_steps, target_sleep_time = :target_sleep_time, target_score = :target_score WHERE user_id = :user_id AND date = :current_date");
            $stmt->bindValue(':target_steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':target_sleep_time', $sleep, SQLITE3_TEXT);
            $stmt->bindValue(':target_score', $score, SQLITE3_INTEGER);
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $stmt->bindValue(':current_date', $current_date, SQLITE3_TEXT);
            $stmt->execute();
        } else {
            $stmt = $sqlite->prepare("INSERT INTO $tbl (user_id, target_steps, target_sleep_time, target_score) VALUES (:user_id, :target_steps, :target_sleep_time, :target_score)");
            $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
            $stmt->bindValue(':target_steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':target_sleep_time', $sleep, SQLITE3_TEXT);
            $stmt->bindValue(':target_score', $score, SQLITE3_INTEGER);
            $stmt->execute();
        }

        header('Location: set_goals_form.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>