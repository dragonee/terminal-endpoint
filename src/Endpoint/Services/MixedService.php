<?php namespace Endpoint\Services;

/**
 * MixedService is a container for both public and protected methods.
 */
class MixedService implements ServiceProxy {
    use Traits\SingleInnerService, Traits\SingleDelegateFunctionCall;

    public function __construct($public_service, $protected_service, $protected_group_name=null) {
        $protected_service = new ProtectedService($protected_service, $protected_group_name);
        
        $service_group = new MultipleService(array($public_service, $protected_service));

        $this->setInnerService($service_group);
    }
}

