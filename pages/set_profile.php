<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $db_file = "../sqlite3.db";
    $tbl = "users";
    $username = $_SESSION['username'];
    $nickname = !empty($_POST['nickname']) ? $_POST['nickname'] : null;
    $height = !empty($_POST['height']) ? $_POST['height'] : null;
    $weight = !empty($_POST['weight']) ? $_POST['weight'] : null;
    $birth = !empty($_POST['birth']) ? $_POST['birth'] : null;
    $gender = !empty($_POST['gender']) ? $_POST['gender'] : null;

    try {
        $sqlite = new SQLite3($db_file);
        $sqlite->enableExceptions(true);

        if ($nickname !== null) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET nickname = :nickname WHERE username = :username");
            $stmt->bindValue(':nickname', $nickname, SQLITE3_TEXT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->execute();
        }

        if ($height !== null) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET height = :height WHERE username = :username");
            $stmt->bindValue(':height', $height, SQLITE3_FLOAT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->execute();
        }

        if ($weight !== null) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET weight = :weight WHERE username = :username");
            $stmt->bindValue(':weight', $weight, SQLITE3_FLOAT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->execute();
        }

        if ($birth !== null) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET date_of_birth = :birth WHERE username = :username");
            $stmt->bindValue(':birth', $birth, SQLITE3_TEXT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->execute();
        }

        if ($gender !== null) {
            $stmt = $sqlite->prepare("UPDATE $tbl SET gender = :gender WHERE username = :username");
            $stmt->bindValue(':gender', $gender, SQLITE3_TEXT);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $stmt->execute();
        }

        header('Location: profile.php');

    } catch (Exception $e) {
        echo "Caught exception: " . $e->getMessage();
    }
}
?>