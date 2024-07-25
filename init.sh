#!/usr/bin/sh

# This is a health initialization tool.
# Process: Create the database and change the appropriate permissions.

sqlite3 < create.sql
chmod +x pages/reset_state.php
chmod a+rw sqlite3.db
chmod a+rw .

TEMP_CRON=$(mktemp)
crontab -l > "$TEMP_CRON"
CURRENT_DIR=$(pwd)
echo "0 0 * * * /usr/bin/php $CURRENT_DIR/pages/reset_state.php" >> "$TEMP_CRON"
crontab "$TEMP_CRON"
rm "$TEMP_CRON"

SERVER_NAME=$(hostname)
PUBLICK_LINK="https://$SERVER_NAME/$CURRENT_DIR"

if [ $? -eq 0 ]; then
    echo "Successfully initislized."
    echo "Please access the following link: $PUBLICK_LINK"
else
    echo "Error: Initialization failed."
fi