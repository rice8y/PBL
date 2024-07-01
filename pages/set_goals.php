<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $db_file = "sqlite3.db";
    $username = $_SESSION['username'];
    $steps = $_POST['steps'];
    $sleep = $_POST['sleep'];
    $score = $_POST['score'];

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
            $stmt = $sqlite->prepare("UPDATE $user_table SET target_steps = :target_steps, target_sleep = :target_sleep, target_score = :target_score WHERE date(time) = :current_date");
            $stmt->bindValue(':target_steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':target_sleep', $sleep, SQLITE3_TEXT);
            $stmt->bindValue(':target_score', $score, SQLITE3_INTEGER);
            $stmt->bindValue(':current_date', $current_date, SQLITE3_TEXT);
            $stmt->execute();
        } else {
            $stmt = $sqlite->prepare("INSERT INTO $user_table (target_steps, target_sleep, target_score) VALUES (:target_steps, :target_sleep, :target_score)");
            $stmt->bindValue(':target_steps', $steps, SQLITE3_INTEGER);
            $stmt->bindValue(':target_sleep', $sleep, SQLITE3_TEXT);
            $stmt->bindValue(':target_score', $score, SQLITE3_INTEGER);
            $stmt->execute();
        }

        header('Location: set_goals_form.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>