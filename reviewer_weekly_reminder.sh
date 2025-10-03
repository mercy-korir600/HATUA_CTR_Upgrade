#!/bin/bash

cd /var/www/ctr || exit 1
/var/www/ctr/app/Console/cake weekly_reviewer_reminder_task >> /var/log/reviewer_weekly_reminder.log 2>&1

