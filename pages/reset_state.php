<?php
try {
    $db_file = "sqlite3.db";
    $sqlite = new SQLite3($db_file);

    $query = "UPDATE check_lists SET state = 0";
    $sqlite->exec($query);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
