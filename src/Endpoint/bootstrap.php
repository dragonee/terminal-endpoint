<?php namespace Endpoint;

Path::setBaseDir(dirname(realpath($_SERVER['SCRIPT_FILENAME'])));
Path::setConfigDir(Path::getBaseDir() . '/etc');
Path::setLibDir(__DIR__);

if(file_exists(Path::getConfigDir() . '/start.php')) {
    require Path::getConfigDir() . '/start.php';
}

