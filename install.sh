#!/bin/bash
composer install
php init --env=Development --overwrite=n
mkdir frontend/web/uploads
chmod -R 777 frontend/web/uploads
vi common/config/main-local.php