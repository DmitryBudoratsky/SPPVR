#!/bin/bash
php yii websocket/stop personal-socket-server
rm ./nohup.out
nohup php yii websocket/start personal-socket-server &