<?php

// DEBUG
define('DEBUG', true);
define('ROOT', dirname(__DIR__));

if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('log_errors', 1);
}
else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
}

require sprintf('%s/vendor/autoload.php', ROOT);

use Core\Application;

$app = new Application;
$app->run();

?>