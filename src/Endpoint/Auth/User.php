<?php namespace Endpoint\Auth;

use Endpoint\Path;

class User {
    protected static $user = null;

    protected
        $username,
        $service_groups = array();

    protected function __construct($username, array $service_groups = array()) {
        $this->username = $username;
        $this->service_groups = $service_groups;
    }

    public static function login($user, $password) {
        $users = require Path::getConfigDir() . '/passwd.php';

        if(!isset($users[$user])) {
            throw new AuthenticationError('Incorrect user credentials');
        }

        if(!password_verify($users[$user]['password'], $password)) {
            throw new AuthenticationError('Incorrect user credentials');
        }

        static::$user = new User($user, $users[$user]['groups']);
    }

    public static function get() {
        return static::$user;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getServiceGroups() {
        return $this->service_groups;
    }

    public function isAuthorized($service) {
        return in_array($service, $this->getServiceGroups());
    }
}
