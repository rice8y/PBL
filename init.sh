#!/usr/bin/sh

sqlite3 < create.sql
chmod +x pages/reset_state.php
chmod a+rw sqlite3.db
chmod a+rw pages
chmod a+rw .

TEMP_CRON=$(mktemp)
crontab -l > "$TEMP_CRON"
CURRENT_DIR=$(pwd)
echo "0 0 * * * /usr/bin/php \"$CURRENT_DIR/pages/reset_state.php\"" >> "$TEMP_CRON"
crontab "$TEMP_CRON"
rm "$TEMP_CRON"