<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $db_file = "sqlite3.db";
    $tbl = "check_lists";
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $item = $_POST['item'];

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        $stmt = $sqlite->prepare("INSERT INTO $tbl (user_id, item) VALUES (:user_id, :item)");
        $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
        $stmt->bindValue(':item', $item, SQLITE3_TEXT);
        $stmt->execute();

        header('Location: checklist.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>