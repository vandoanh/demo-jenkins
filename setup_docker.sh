detach_mode=$1

echo "Run docker"
cp docker/.env.example docker/.env;
cd docker;
if [ "$detach_mode" = "none" ]; then
    docker-compose up --build
else
    docker-compose up -d --build
fi

###########################################################################
# Setup project
###########################################################################
echo "Copy .env.docker to .env"
docker-compose exec php-fpm cp .env.docker .env;

echo "Run composer install.";
docker-compose exec php-fpm composer install;

echo "Create App key.";
docker-compose exec php-fpm php artisan key:generate --ansi;

echo "Create storage folder and sub folder: app, framework (cache, sessions, views), logs.";
docker-compose exec php-fpm mkdir storage;
docker-compose exec php-fpm mkdir storage/app;
docker-compose exec php-fpm mkdir storage/framework;
docker-compose exec php-fpm mkdir storage/framework/cache;
docker-compose exec php-fpm mkdir storage/framework/sessions;
docker-compose exec php-fpm mkdir storage/framework/views;
docker-compose exec php-fpm mkdir storage/logs;

echo "Change mod write 777 for folder and sub of bootstrap and storage folder.";
docker-compose exec php-fpm chmod -R 777 bootstrap/cache storage;

echo "Run npm install -g pm2.";
docker-compose exec php-fpm npm install -g pm2

echo "Run npm install && npm run production.";
docker-compose exec php-fpm npm install;
docker-compose exec php-fpm npm run production;

echo "Run pm2 start queue.sh to monitor queue worker.";
docker-compose exec php-fpm pm2 start queue.sh -l --log storage/logs/worker.log

echo "Run migrate & db seed"
docker-compose exec php-fpm php artisan migrate;
docker-compose exec php-fpm php artisan passport:install;
docker-compose exec php-fpm php artisan db:seed;
docker-compose exec php-fpm php artisan es:index-mapping;
docker-compose exec php-fpm php artisan crawler:news;
docker-compose exec php-fpm pm2 monit;

