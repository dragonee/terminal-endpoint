<?php namespace Endpoint\Services\Traits;

trait SingleInnerService {
    protected $service = null;

    public function getInnerServices() {
        return array($this->service);
    }

    public function getInnerService() {
        return $this->service;
    }

    public function setInnerService($service) {
        $this->service = $service;
    }
}
