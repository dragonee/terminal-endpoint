<?php namespace Endpoint\Services;

use Endpoint\Auth\AuthenticationError;

class MultipleService implements ServiceProxy {
    use Traits\MultipleInnerServices, Traits\MultipleDelegateFunctionCall;
    
    public function __construct(array $services) {
        $this->setInnerServices($services);
    }

    // Merging help
    public function help() {
        $parameters = func_get_args();
        $help = array();
        
        foreach($this->getInnerServices() as $service) {
            try {
                $help += @call_user_func_array(array($service, 'help'), $parameters);
            } catch(AuthenticationError $e) {
            }
        }

        ksort($help);

        return $help;
    }
}
