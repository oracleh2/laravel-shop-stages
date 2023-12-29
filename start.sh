#!/bin/sh
php artisan storage:link &&
composer install &&
npm install &&
npm run build &&
php artisan telescope:install &&
php artisan install
