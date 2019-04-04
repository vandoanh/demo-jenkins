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
winpty docker-compose exec php-fpm cp .env.docker .env;

echo "Run composer install.";
winpty docker-compose exec php-fpm composer install;

echo "Create App key.";
winpty docker-compose exec php-fpm php artisan key:generate --ansi;

echo "Create storage folder and sub folder: app, framework (cache, sessions, views), logs.";
winpty docker-compose exec php-fpm mkdir storage;
winpty docker-compose exec php-fpm mkdir storage/app;
winpty docker-compose exec php-fpm mkdir storage/framework;
winpty docker-compose exec php-fpm mkdir storage/framework/cache;
winpty docker-compose exec php-fpm mkdir storage/framework/sessions;
winpty docker-compose exec php-fpm mkdir storage/framework/views;
winpty docker-compose exec php-fpm mkdir storage/logs;

echo "Change mod write 777 for folder and sub of bootstrap and storage folder.";
winpty docker-compose exec php-fpm chmod -R 777 bootstrap/cache storage;

echo "Run npm install -g pm2.";
winpty docker-compose exec php-fpm npm install -g pm2

echo "Run npm install && npm run production.";
winpty docker-compose exec php-fpm npm install;
winpty docker-compose exec php-fpm npm run production;

echo "Run pm2 start queue.sh to monitor queue worker.";
winpty docker-compose exec php-fpm pm2 start queue.sh -l --log storage/logs/worker.log

echo "Run migrate & db seed"
winpty docker-compose exec php-fpm php artisan migrate;
winpty docker-compose exec php-fpm php artisan passport:install;
winpty docker-compose exec php-fpm php artisan db:seed;
winpty docker-compose exec php-fpm php artisan es:index-mapping;
winpty docker-compose exec php-fpm php artisan crawler:news;
winpty docker-compose exec php-fpm pm2 monit;

