<?php
class Router {
    protected $currentController = 'AuthController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Check if user is logged in for default routing
        if (isset($_SESSION['user_id']) && empty($url)) {
            if ($_SESSION['role'] === 'Admin') {
                $this->currentController = 'AdminController';
            } else {
                $this->currentController = 'EmployeeController';
            }
        }

        // Check if controller exists
        if(isset($url[0]) && file_exists(__DIR__ . '/../controllers/' . ucwords($url[0]). 'Controller.php')){
            $this->currentController = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once __DIR__ . '/../controllers/'. $this->currentController . '.php';
        $this->currentController = new $this->currentController;

        // Check if method exists in controller
        if(isset($url[1])){
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}
