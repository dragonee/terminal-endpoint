<?php namespace Endpoint\Services;

use Endpoint\Auth\User;
use Endpoint\Auth\AuthenticationError;

class ProtectedService implements ServiceProxy {
    use Traits\SingleInnerService;
    
    protected $group_name;

    public function __construct($object, $group_name=null) {
        $this->setInnerService($object);
        $this->group_name = $group_name;
    }

    public function __call($method, $args) {
        if(count($args) == 0) {
            throw new AuthenticationError('This service needs authentication');
        }

        $token = array_shift($args);
        $token_bits = explode(':', $token);

        if(count($token_bits) != 2) {
            throw new AuthenticationError('Incorrect user credentials');
        }

        User::login($token_bits[0], $token_bits[1]);

        if($this->group_name && !User::get()->isAuthorized($this->group_name)) {
            throw new AuthenticationError('You are not authorized to run this method');
        }

        return @call_user_func_array(array($this->getInnerService(), $method), $args);
    }
}
