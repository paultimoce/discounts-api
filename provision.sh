composer install;
touch database/database.sqlite;
php artisan migrate:install;
php artisan migrate;
php artisan db:seed;
php artisan make:auth;
php artisan passport:install;