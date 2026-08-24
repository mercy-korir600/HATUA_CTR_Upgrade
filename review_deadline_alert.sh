#!/bin/bash

cd /var/www/ctr || exit 1
/var/www/ctr/app/Console/cake review_deadline_alert >> /var/log/review_deadline_alert.log 2>&1
