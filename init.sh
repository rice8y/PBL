#!/usr/bin/sh

# =======================================================================================================================
# This is a health initialization tool.
# Process: Create the database and change the appropriate permissions. 
#          Also, change the path to the database file in reset_state.php.
# Note: The link displayed upon successful initialization assumes that the server in use Ehime University's web server.
# =======================================================================================================================

sqlite3 < create.sql
chmod +x pages/reset_state.php
chmod a+rw sqlite3.db
chmod a+rw .

FILE="pages/reset_state.php"
CURRENT_DIR=$(pwd)
LINE=3
TARGET="db_abs_path"
REPLACE="$CURRENT_DIR/sqlite3.db"

sed -i "${LINE}s|${TARGET}|${REPLACE}|g" "$FILE"

TEMP_CRON=$(mktemp)
crontab -l > "$TEMP_CRON"
echo "0 0 * * * /usr/bin/php $CURRENT_DIR/pages/reset_state.php" >> "$TEMP_CRON"
crontab "$TEMP_CRON"
rm "$TEMP_CRON"

if echo "$CURRENT_DIR" | grep -q 'public_html'; then
    RELATIVE_PATH=$(echo "$CURRENT_DIR" | sed -n "s/.*public_html\(.*\)/\1/p")
else
    echo "The directory 'public_html' is not found in the current directory path."
    exit 1
fi

SERVER_NAME=$(hostname)
USER_NAME=$(whoami)
PUBLICK_LINK="https://$SERVER_NAME/~$USER_NAME$RELATIVE_PATH/pages/login_form.php"

if [ $? -eq 0 ]; then
    echo "Successfully initialized."
    echo "Please access the following link: $PUBLICK_LINK"
else
    echo "Error: Initialization failed."
fi