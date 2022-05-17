<?php
ini_set("display_errors", "On");
$config = file_get_contents(dirname(__FILE__)."/config.json");
$config = json_decode($config);
require_once "vendor/autoload.php";

spl_autoload_register(function ($className) {
    $dirs = [
        "Commands",
        "ApiRedis",
        "Services/DataSources"
    ];
    foreach ($dirs as $oneDir) {
        if (file_exists(dirname(__FILE__)."/" . $oneDir . "/" . $className.".php")) {
            require_once dirname(__FILE__)."/" . $oneDir . "/" . $className.".php";
            return true;
        }
    }
    throw new Exception("Class ".$oneDir . "/" . $className.".php not exists".PHP_EOL);
});