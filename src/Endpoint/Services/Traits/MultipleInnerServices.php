<?php namespace Endpoint\Services\Traits;

trait MultipleInnerServices {
    protected $services = array();

    public function getInnerServices() {
        return $this->services;
    }

    public function addInnerService($service) {
        $this->services[] = $service;
    }

    public function setInnerServices(array $services) {
        $this->services = $services;
    }
}
