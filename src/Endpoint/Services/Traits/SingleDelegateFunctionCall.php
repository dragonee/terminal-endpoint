<?php namespace Endpoint\Services\Traits;

trait SingleDelegateFunctionCall {
    public function __call($name, $parameters) {
        return @call_user_func_array(array($this->getInnerService(), $name), $parameters);
    }

    abstract function getInnerService();
}
