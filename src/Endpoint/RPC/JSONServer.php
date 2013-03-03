<?php namespace Endpoint\RPC;

// CORS-enabled RPC server
class JSONServer {
    protected $object,
        $origins = array(),
        $request_id = null;

	public function __construct($object) {
        $this->object = $object;
    }

    public function setOrigins(array $origins) {
        $this->origins = $origins;
    }

    public function addOrigin($origin) {
        $this->origins[] = $origin;
    }

    public function getOrigins() {
        return $this->origins;
    }

    protected function setId($id) {
        $this->request_id = $id;
    }

    protected function getId() {
        return $this->request_id;
    }

    protected function readableCall() {
        header('Content-Type: application/json; charset: utf-8');
        
        return $this->invalidCall('This service is not accessible via GET method.');
    }

    protected function invalidCall($message) {
        header('HTTP/1.0 400 Bad Request');

        return $this->error($message);
    }

    protected function preflight() {
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: accept, origin, content-type');

        if(in_array('*', $this->origins)) {
            header('Access-Control-Allow-Origin: *');
        } else if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $this->origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        }

        header('Access-Control-Max-Age: 3600');

        header('Content-Type: application/json; charset=utf-8');
    }

    protected function response($result) {
        return json_encode(array(
            'jsonrpc' => '2.0',
            'id' => $this->getId(),
            'result' => $result,
            'error' => null,
        ));
    }

    protected function error($message) {
        return json_encode(array(
            'jsonrpc' => '2.0',
            'id' => $this->getId(),
            'result' => null,
            'error' => $message,
        ));
    }

    public function handle() {
        // handle CORS preflight
        if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $this->preflight();
        }
        
        // somebody has accessed this endpoint via browser
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            return $this->readableCall();
        }
       
        if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $this->origins)) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
        }

        // since here, we need to return JSON
        header('Content-Type: application/json; charset: utf-8');

        // drop any other method
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            return $this->invalidCall('Only POST calls are supported.');
        }

        // do not allow content-types that are not application/json
        if(!isset($_SERVER['CONTENT_TYPE']) || strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== 0) {
            return $this->invalidCall('Only application/json is supported as Content-Type');
        }

		$request = json_decode(file_get_contents('php://input'), true);

        if(isset($request['id'])) {
            $this->setId($request['id']);
        }

        // validate all parts of the request
	    if(!isset($request['method']) || !isset($request['params']) || !isset($request['jsonrpc']) || !is_array($request['params'])) {
            return $this->invalidCall('Malformed JSON-RPC request');
        }

        // validate version
        if($request['jsonrpc'] != '2.0') {
            return $this->invalidCall('Invalid JSON-RPC version, must be set to 2.0');
        }

        $method = $request['method'];
        $params = $request['params'];
        
		// executes the task on local object
		try {
			$result = @call_user_func_array(array($this->object, $method), $params);
        } catch(\Exception $e) {
            $class = get_class($e);

            return $this->error("$class: {$e->getMessage()}.");
        }

        // you can't return null or false on your callback. Thank PHP.
        if($result === false || $result === null) {
            return $this->error("Unknown method or incorrect parameters.");
        }
        
        if($this->getId()) {
            return $this->response($result);
        }
	}
}
?>
