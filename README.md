git clone
composer install/update
composer dump-autoload
php bin/console d:d:c
php bin/console d:m:m
php bin/console doctrine:fixtures:load
symfony server:start

BDD mysql
