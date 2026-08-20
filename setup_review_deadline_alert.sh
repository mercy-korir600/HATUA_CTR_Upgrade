#!/bin/bash

set -e

# === CONFIG ===
SCRIPT_NAME="review_deadline_alert.sh"
SCRIPT_PATH="/usr/local/bin/$SCRIPT_NAME"
LOG_FILE="/var/log/review_deadline_alert.log"
SERVICE_NAME="review-deadline-alert.service"
TIMER_NAME="review-deadline-alert.timer"
APP_DIR="/var/www/ctr"
CAKE_CMD="review_deadline_alert"
PHP_BIN="/usr/bin/php"

# === Create the executable script ===
echo "Creating $SCRIPT_PATH..."
cat <<EOF | sudo tee "$SCRIPT_PATH" > /dev/null
#!/bin/bash

echo "[\$(date)] Review deadline alert task starting... (user: \$(whoami))" >> $LOG_FILE

cd $APP_DIR || {
  echo "[\$(date)] ❌ Could not cd into $APP_DIR" >> $LOG_FILE
  exit 1
}

./app/Console/cake $CAKE_CMD >> $LOG_FILE 2>&1

echo "[\$(date)] ✅ Review deadline alert task completed." >> $LOG_FILE
EOF

# Make it executable
sudo chmod +x "$SCRIPT_PATH"

# === Create systemd service ===
echo "Creating /etc/systemd/system/$SERVICE_NAME..."
cat <<EOF | sudo tee /etc/systemd/system/$SERVICE_NAME > /dev/null
[Unit]
Description=Run CakePHP Review stage deadline alert engine (50%/70%/100%/overdue + CAPA)
After=network.target

[Service]
Type=oneshot
User=www-data
ExecStart=$SCRIPT_PATH
EOF

# === Create systemd timer ===
# Percentage tiers are day-granularity (of a 30-day SLA), so once/day is
# sufficient - the shell itself is idempotent if the timer ever double-fires.
echo "Creating /etc/systemd/system/$TIMER_NAME..."
cat <<EOF | sudo tee /etc/systemd/system/$TIMER_NAME > /dev/null
[Unit]
Description=Run Review stage deadline alert engine daily at 7AM

[Timer]
OnCalendar=*-*-* 07:00:00
Unit=$SERVICE_NAME

[Install]
WantedBy=timers.target
EOF

# === Reload and enable the timer ===
echo "Reloading systemd and enabling timer..."
sudo systemctl daemon-reload
sudo systemctl enable --now "$TIMER_NAME"

echo "✅ Setup complete!"
systemctl list-timers --all | grep review-deadline-alert
