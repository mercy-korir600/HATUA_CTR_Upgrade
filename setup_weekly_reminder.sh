#!/bin/bash

set -e

# === CONFIG ===
SCRIPT_NAME="reviewer_weekly_reminder.sh"
SCRIPT_PATH="/usr/local/bin/$SCRIPT_NAME"
LOG_FILE="/var/log/reviewer_weekly_reminder.log"
SERVICE_NAME="weekly-reminder.service"
TIMER_NAME="weekly-reminder.timer"
APP_DIR="/var/www/ctr"
CAKE_CMD="weekly_reviewer_reminder_task"
PHP_BIN="/usr/bin/php"

# === Create the executable script ===
echo "Creating $SCRIPT_PATH..."
cat <<EOF | sudo tee "$SCRIPT_PATH" > /dev/null
#!/bin/bash

echo "[\$(date)] Reminder task starting... (user: \$(whoami))" >> $LOG_FILE

cd $APP_DIR || {
  echo "[\$(date)] ❌ Could not cd into $APP_DIR" >> $LOG_FILE
  exit 1
}

./app/Console/cake $CAKE_CMD >> $LOG_FILE 2>&1

echo "[\$(date)] ✅ Reminder task completed." >> $LOG_FILE
EOF

# Make it executable
sudo chmod +x "$SCRIPT_PATH"

# === Create systemd service ===
echo "Creating /etc/systemd/system/$SERVICE_NAME..."
cat <<EOF | sudo tee /etc/systemd/system/$SERVICE_NAME > /dev/null
[Unit]
Description=Run CakePHP weekly reviewer reminder task
After=network.target

[Service]
Type=oneshot
User=www-data
ExecStart=$SCRIPT_PATH
EOF

# === Create systemd timer ===
echo "Creating /etc/systemd/system/$TIMER_NAME..."
cat <<EOF | sudo tee /etc/systemd/system/$TIMER_NAME > /dev/null
[Unit]
Description=Run weekly reviewer reminder every Monday at 6AM

[Timer]
OnCalendar=Mon *-*-* 06:00:00
Unit=$SERVICE_NAME

[Install]
WantedBy=timers.target
EOF

# === Reload and enable the timer ===
echo "Reloading systemd and enabling timer..."
sudo systemctl daemon-reload
sudo systemctl enable --now "$TIMER_NAME"

echo "✅ Setup complete!"
systemctl list-timers --all | grep weekly-reminder
