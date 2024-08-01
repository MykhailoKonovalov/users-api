<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

echo "Clearing cache" . PHP_EOL;
exec("php bin/console ca:cl --env=test --no-interaction");
echo "Recreating test database" . PHP_EOL;
exec("php bin/console d:d:d --env=test --no-interaction --force");
exec("php bin/console d:d:c --env=test --no-interaction");
exec("php bin/console d:s:u --env=test --no-interaction --force");
exec("php bin/console d:f:l --env=test --no-interaction");
echo PHP_EOL;
