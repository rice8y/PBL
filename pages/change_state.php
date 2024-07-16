<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db_file = "sqlite3.db";
    $tbl = "check_lists";
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $list_id = $_POST['list_id'];
    $state = $_POST['state'];

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        $stmt = $sqlite->prepare("UPDATE $tbl SET state = :state WHERE list_id = :list_id AND user_id = :user_id");
        $stmt->bindValue(':state', $state, SQLITE3_INTEGER);
        $stmt->bindValue(':list_id', $list_id, SQLITE3_INTEGER);
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->execute();

        header('Location: checklist.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>