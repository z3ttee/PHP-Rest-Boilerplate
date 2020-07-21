<?
namespace App\Models;

class Response {
    private static $_instance;
    private $_data,
            $_status;

    public function __construct() {
        $this->_status = array("code" => 200, "message" => "OK");
        $this->_data = array();
    }

    public function setCode(int $code) {
        $this->_status["code"] = $code;
    }
    public function setMessage(string $message) {
        $this->_status["message"] = $message;
    }
    public function setAdditional(string $key, $value) {
        $this->_status[$key] = $value;
    }
    public function setData(array $data) {
        $this->_data = $data;
    }

    public function print() {
        echo json_encode(array("status" => (object) $this->_status, "data" => $this->_data));
    }

    public function status() {
        return $this->_status;
    }
    public function data() {
        return $this->_data;
    }

    public static function getInstance() {
        if(self::$_instance == null) self::$_instance = new Response();
        return self::$_instance;
    }
}
?>