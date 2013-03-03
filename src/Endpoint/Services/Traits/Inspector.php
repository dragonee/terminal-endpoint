<?php namespace Endpoint\Services\Traits;

trait Inspector {
    /**
     * display this message
     */
    public function help() {
        $r = new \ReflectionClass(get_class($this));

        $methods = array();
        foreach($r->getMethods() as $method) {
            if($method->isStatic() || !$method->isPublic()) {
                continue;
            }

            $comment = $method->getDocComment();
            if(!$comment) {
                continue;
            }

            $params = array();
            foreach($method->getParameters() as $parameter) {
                $param = strtoupper($parameter->getName());

                if($parameter->isOptional()) {
                    $params[] = "[$param]";
                } else {
                    $params[] = $param;
                }
            }

            $name = join(' ', array_merge(array($method->getName()), $params));

            // strip first line
            strtok($comment, "\n");
            // get the second one
            $methods[$name] = trim(strtok("\n"), '* ');
        }

        return $methods;
    }
}

