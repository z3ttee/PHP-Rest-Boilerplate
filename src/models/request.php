<?
namespace App\Models;

class Request {
    private static $_instance;
    private $_params,
            $_version,
            $_endpoint;

    public function __construct() {
        $methodType = $_SERVER['REQUEST_METHOD'];
        $query = explode("/",$_SERVER['QUERY_STRING']);

        $this->_params = $_GET;

        if(count($this->_params) < 1) {
            throw new \Exception("Invalid url format");
        }

        $version = $query[0];
        

        if(is_null($version) || empty($version) || !startsWith($version, 'v')) {
            throw new \Exception("Invalid api version provided");
        }

        $endpoint = \explode("&", $query[1])[0];

        if(is_null($endpoint) || empty($endpoint)) {
            throw new \Exception("Invalid endpoint");
        }

        
        $this->_version = $version;
        $this->_endpoint = $endpoint;
    }

    public function process() {
        $namespace = 'App\\Endpoint\\'.ucfirst($this->_version).'\\';

        $className = $namespace.ucfirst($this->_endpoint).'Endpoint';
        $endpoint = new $className();

        if(!$endpoint->requiresAuthenticated()) {
            $endpoint->process();
        } else {
            if($this->authenticate()) {
                $endpoint->process();
            } else {
                throw new \Exception("No permission");
            }
        }
    }

    private function authenticate() {
        if(!isset(getallheaders()["Authorization"]) && !isset($_GET['access_token'])) {
            throw new \Exception("Could not find authorization header");
        }

        if(isset($_GET['access_token'])) {
            $bearerCode = $_GET['access_token'];
        } else {
            $bearerCode = getallheaders()["Authorization"];
        }
        
        $bearerCode = \str_replace("Bearer ", "", $bearerCode);

        if(\is_null($bearerCode)) {
            throw new \Exception("Could not authenticate");
        }

        if(!Database::getInstance()->hasConnection()) {
            throw new \Exception("database unavailable");
        }

        // Table: userID / token / expiry
        $result = Database::getInstance()->get('access_tokens', array('token', '=', $bearerCode));
        if(Database::getInstance()->error() || Database::getInstance()->count() == 0) {
            throw new \Exception("invalid access token");
        }

        $result = $result->first();
        $expiry = $result->expiry;

        $currentTime = round(microtime(true) * 1000);
        if($expiry <= $currentTime) {
            throw new \Exception("token expired");
        }

        return true;
    }

    public static function getInstance() {
        if(self::$_instance == null) self::$_instance = new Request();
        return self::$_instance;
    }
}
?>