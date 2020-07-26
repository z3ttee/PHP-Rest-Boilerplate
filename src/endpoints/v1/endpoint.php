<?
namespace App\Endpoint\v1;

abstract class Endpoint {
    abstract function process();
    abstract function requiresAuthenticated();
}
?>