<?
use App\Models\Request;
use App\Models\Response;
use App\Models\Config;

session_start();
require './vendor/autoload.php';
require_once 'src/functions/sanatize.php';
require_once 'src/functions/manipulation.php';

define("API_ROOT", __DIR__);

header('Content-Type: application/json');

set_exception_handler(function($exception){
    Response::getInstance()->setCode(400);
    Response::getInstance()->setMessage(strtolower($exception->getMessage() ?: "Failed processing the request"));

    if($exception instanceof NotFoundException) {
        Response::getInstance()->setCode(404);
    }

    Response::getInstance()->print();
    die;
});

set_error_handler(function($errorCode, $errorText, $errorFile, $errorLine){
    Response::getInstance()->setCode(400);
    Response::getInstance()->setMessage(strtolower($errorText));

    if(Config::get("devmode")) {
        Response::getInstance()->setAdditional("file", $errorFile);
        Response::getInstance()->setAdditional("line", $errorLine);
    }

    Response::getInstance()->print();
    die;
}, E_ALL ^ E_USER_DEPRECATED);

Request::getInstance()->process();
Response::getInstance()->print();