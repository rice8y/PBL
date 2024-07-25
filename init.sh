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

if echo "$CURRENT_DIR" | grep -q 'public_html'; then
    RELATIVE_PATH=$(echo "$CURRENT_DIR" | sed -n "s/.*public_html\(.*\)/public_html\1/p")
else
    echo "The directory 'public_html' is not found in the current directory path."
    exit 1
fi

SERVER_NAME=$(hostname)
USER_NAME=$(whoami)
PUBLICK_LINK="https://$SERVER_NAME~$USER_NAME$RELATIVE_PATH/pages/login_form.php"

if [ $? -eq 0 ]; then
    echo "Successfully initialized."
    echo "Please access the following link: $PUBLICK_LINK"
else
    echo "Error: Initialization failed."
fi