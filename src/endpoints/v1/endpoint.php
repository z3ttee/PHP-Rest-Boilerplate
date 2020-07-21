<?
namespace App\Endpoint\v1;

abstract class Endpoint {
    abstract protected function process();
    abstract protected function requiresAuthenticated();
}
?>