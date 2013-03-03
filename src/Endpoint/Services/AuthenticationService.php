<?php namespace Endpoint\Services;

use Endpoint\Auth\User;

class AuthenticationService {
    use Traits\Inspector;
    
    public function login($user, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        User::login($user, $password);
        
        return array('token' => "$user:$password");
    }
}
