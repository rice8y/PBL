<?php
try {
    $db_file = "db_abs_path";
    $tbl = "check_lists";
    $sqlite = new SQLite3($db_file, SQLITE3_OPEN_READWRITE);
    $sqlite->enableExceptions(true);

    $stmt = $sqlite->prepare("UPDATE $tbl SET state = :state");
    $stmt->bindValue(':state', 0, SQLITE3_INTEGER);
    $result = $stmt->execute();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
