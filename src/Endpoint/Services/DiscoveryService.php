<?php namespace Endpoint\Services;

use Endpoint\Path;
use Endpoint\Auth\User;

class DiscoveryService {
    use Traits\Inspector;

    /**
     * discover all available services
     */
    public function discover() {
        $services = array();
        $service_list = require Path::getConfigDir() . '/services.php';

        foreach($service_list as $service_name => $service_options) {
            foreach($service_options['groups'] as $group) {
                if(User::get()->isAuthorized($group)) {
                    $services[$service_name] = $service_options['description'];
                    break;
                }
            }
        }

        return $services;
    }
}

