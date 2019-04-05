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
docker-compose exec jenkins bash
cd /var/jenkins_home/workspace/demo-jenkins/do

docker-compose exec php-fpm bash
cat /etc/os-release
cp .env.docker .env;

echo "Run composer install.";
composer install;

echo "Create App key.";
php artisan key:generate --ansi;

echo "Create storage folder and sub folder: app, framework (cache, sessions, views), logs.";
mkdir storage;
mkdir storage/app;
mkdir storage/framework;
mkdir storage/framework/cache;
mkdir storage/framework/sessions;
mkdir storage/framework/views;
mkdir storage/logs;

echo "Change mod write 777 for folder and sub of bootstrap and storage folder.";
chmod -R 777 bootstrap/cache storage;

echo "Run npm install -g pm2.";
npm install -g pm2

echo "Run npm install && npm run production.";
npm install;
npm run production;

echo "Run pm2 start queue.sh to monitor queue worker.";
pm2 start queue.sh -l --log storage/logs/worker.log

echo "Run migrate & db seed"
php artisan migrate;
php artisan passport:install;
php artisan db:seed;
php artisan es:index-mapping;
php artisan crawler:news;
exit;
# pm2 monit;

