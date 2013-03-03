<?php namespace Endpoint\Services\Traits;

trait MultipleDelegateFunctionCall {
    public function __call($name, $parameters) {
        foreach($this->getInnerServices() as $service) {
            $result = @call_user_func_array(array($service, $name), $parameters);

            // if no callback, call_user_func_array returns null
            // false otherwise if something fails
            if($result !== false && $result !== null) {
                return $result;
            }
        }

        return false;
    }
    abstract function getInnerServices();
}
