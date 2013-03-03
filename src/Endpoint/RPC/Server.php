<?php namespace Endpoint\RPC;

use Endpoint\Path;

class Server extends JSONServer {
    public function __construct($object) {
        $this->setOrigins(require Path::getConfigDir() . '/origins.php');

        parent::__construct($object);
    }

    public static function serve($object) {
        $server = new static($object);
        echo $server->handle();
    }
    
    protected function readableCall() {
        $file_path = Path::getConfigDir() . '/endpoint-page.php';

        if(!file_exists($file_path)) {
            $file_path = Path::getLibDir() . '/endpoint-page.php';
        } 

        include $file_path;
    }
}

