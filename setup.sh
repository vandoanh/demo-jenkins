#!/bin/sh

echo "Copy .env.example to .env"
cp .env.example .env;
vi .env;

echo "Run composer install.";
composer install;

echo "Create App key.";
php artisan key:generate --ansi;

echo "Create storage folder and sub folder: app, framework (cache, sessions, views), logs.";
sudo mkdir storage;
sudo mkdir storage/app;
sudo mkdir storage/framework;
sudo mkdir storage/framework/cache;
sudo mkdir storage/framework/sessions;
sudo mkdir storage/framework/views;
sudo mkdir storage/logs;

echo "Change mod write 777 for folder and sub of bootstrap and storage folder.";
sudo chmod -R 777 bootstrap/cache storage;

echo "Run php artisan migrate, php artisan passport:install && php artisan db:seed.";
php artisan migrate;
php artisan passport:install;
php artisan db:seed;
php artisan es:index-mapping;

echo "Run npm install -g pm2.";
npm install -g pm2;

echo "Run npm install && npm run production.";
npm install;
npm run production;
