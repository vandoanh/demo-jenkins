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

docker-compose exec -T php-fpm cp .env.docker .env;

echo "Run composer install.";
docker-compose exec -T php-fpm composer install;

echo "Create App key.";
docker-compose exec -T php-fpm php artisan key:generate --ansi;

echo "Create storage folder and sub folder: app, framework (cache, sessions, views), logs.";
docker-compose exec -T php-fpm mkdir storage;
docker-compose exec -T php-fpm mkdir storage/app;
docker-compose exec -T php-fpm mkdir storage/framework;
docker-compose exec -T php-fpm mkdir storage/framework/cache;
docker-compose exec -T php-fpm mkdir storage/framework/sessions;
docker-compose exec -T php-fpm mkdir storage/framework/views;
docker-compose exec -T php-fpm mkdir storage/logs;

echo "Change mod write 777 for folder and sub of bootstrap and storage folder.";
docker-compose exec -T php-fpm chmod -R 777 bootstrap/cache storage;

echo "Run npm install -g pm2.";
docker-compose exec -T php-fpm npm install -g pm2

echo "Run npm install && npm run production.";
docker-compose exec -T php-fpm npm install;
docker-compose exec -T php-fpm npm run production;

echo "Run pm2 start queue.sh to monitor queue worker.";
docker-compose exec -T php-fpm pm2 start queue.sh -l --log storage/logs/worker.log

echo "Run migrate & db seed"
docker-compose exec -T php-fpm php artisan migrate;
docker-compose exec -T php-fpm php artisan passport:install;
docker-compose exec -T php-fpm php artisan db:seed;
docker-compose exec -T php-fpm php artisan es:index-mapping;
docker-compose exec -T php-fpm php artisan crawler:news;
docker-compose exec -T php-fpm pm2 monit;

