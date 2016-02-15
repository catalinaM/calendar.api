<?php
namespace App;

class FrontController
{

    protected $controller;
    protected $action;
    protected $params = array();
    protected $basePath = "/var/www/calendarApp";


    public function __construct(array $options = array()) {
        if (empty($options)) {
            $this->parseUri();
        }
        else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }
            if (isset($options["action"])) {
                $this->setAction($options["action"]);
            }
            if (isset($options["params"])) {
                $this->setParams($options["params"]);
            }
        }
    }

    protected function parseUri() {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $options = explode("/", $path, 3);
        if (count($options) > 2){
            list($controller, $action, $params) = $options;
        } else if (count($options) == 2) {
            list($controller, $params) = $options;
            $action = $this->getActionByMethodType();
        } else {
            $controller = $options[0];
            $action = $this->getActionByMethodType();
        }

        if (isset($controller)) {
            $this->setController($controller);
        }
        if (isset($action)) {
            $this->setAction("{$action}Action");
        }
        if (isset($params)) {
            $this->setParams(explode("/", $params));
        }

    }

    public function setController($controller) {
        $controller = "Controllers\\" . ucfirst(strtolower($controller)) . "Controller";
        if (!class_exists($controller, true)) {
            throw new \InvalidArgumentException(
                "The action controller '$controller' has not been defined.");
        }
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action) {
        $reflector = new \ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) {
            throw new \InvalidArgumentException(
                "The controller action '$action' has been not defined.");
        }
        $this->action = $action;
        return $this;
    }

    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }

    private function getActionByMethodType(){
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $method = 'PUT';
            } else {
                throw new \Exception("Unexpected Header");
            }
        }
        $method = strtolower($method);

        return $method;
    }

    public function run() {
        call_user_func_array(array(new $this->controller, $this->action), $this->params);
    }
}