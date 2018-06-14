composer install;
touch database/database.sqlite;
cp env.example .env;
php artisan key:generate;
php artisan migrate:install;
php artisan migrate;
php artisan db:seed;
php artisan make:auth;
php artisan passport:install;