<?php namespace Endpoint\Services;

class EndpointService extends MixedService {
    public function __construct() {
        parent::__construct(new AuthenticationService, new DiscoveryService);
    }
}
