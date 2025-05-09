#!/bin/bash
git pull
php yii migrate --interactive=0
./websockets-restart.sh