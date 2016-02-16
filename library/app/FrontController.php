<?php
namespace App;
use Pux\Executor;
use Pux\Mux;

class FrontController
{

    protected $route;
    protected $controller;

    public function __construct(array $options = array()) {
        if (empty($options)) {
            $this->parseUri();
        }
        else {
            if (isset($options["controller"])) {
                $this->setController($options["controller"]);
            }
        }

    }

    protected function parseUri() {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        $this->route = $path;
        $options = explode("/", $path, 3);

        if (isset($options[0])) {
            $this->setController($options[0]);
        }
    }
    private function getControllerClass($controller){
        return "Controllers\\" . ucfirst(strtolower($controller)) . "Controller";
    }

    public function setController($controller) {
        $controllerClass = $this->getControllerClass($controller);
        if (!class_exists($controllerClass, true)) {
            throw new \InvalidArgumentException(
                "The action controller '$controllerClass' has not been defined.");
        }
        $this->controller = $controller;
        return $this;
    }

    public function run() {
        $controllerClass = $this->getControllerClass($this->controller);
        $controller = new $controllerClass();
        $mux = $controller->expand();
        $route = $mux->dispatch($this->route);

        if (!$route) {
            throw new \InvalidArgumentException(
                "The route {$this->route} has not been defined.");
        }

        Executor::execute($route);
    }
}