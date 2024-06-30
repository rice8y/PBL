<?php
$db_file = "sqlite3.db";
$tbl_name = "userinfo";

try {
    $sqlite = new SQLite3($db_file);
    $sqlite->enableExceptions(true);

    $sqlite->exec("CREATE TABLE IF NOT EXISTS " . $tbl_name . " (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        time_col TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime'))
    )");

    $sqlite->close();
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}
?>