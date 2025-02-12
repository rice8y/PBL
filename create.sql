.open sqlite3.db

CREATE TABLE users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    nickname TEXT,
    password TEXT NOT NULL,
    date_of_birth DATE,
    gender TEXT,
    height DECIMAL(5, 2),
    weight DECIMAL(5, 2),
    created_at TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
    updated_at TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime'))
);

CREATE TRIGGER update_users_updated_at
AFTER UPDATE ON users
FOR EACH ROW
WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE users SET updated_at = datetime(CURRENT_TIMESTAMP, 'localtime') WHERE user_id = OLD.user_id;
END;


CREATE TABLE health_records (
    record_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    date DATE DEFAULT (date(CURRENT_TIMESTAMP, 'localtime')),
    steps INTEGER,
    target_steps INTEGER,
    sleep_time TIME,
    target_sleep_time TIME,
    target_score INTEGER,
    created_at TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
    updated_at TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TRIGGER update_health_records_updated_at
AFTER UPDATE ON health_records
FOR EACH ROW
WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE health_records SET updated_at = datetime(CURRENT_TIMESTAMP, 'localtime') WHERE user_id = OLD.user_id;
END;

CREATE TABLE check_lists (
    list_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    date DATE DEFAULT (date(CURRENT_TIMESTAMP, 'localtime')),
    item TEXT,
    state BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT (datetime('now', 'localtime')),
    updated_at TIMESTAMP DEFAULT (datetime('now', 'localtime')),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

CREATE TRIGGER update_check_lists_updated_at
AFTER UPDATE ON check_lists
FOR EACH ROW
WHEN NEW.updated_at <= OLD.updated_at
BEGIN
    UPDATE check_lists SET updated_at = datetime(CURRENT_TIMESTAMP, 'localtime') WHERE user_id = OLD.user_id;
END;