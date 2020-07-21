<?
namespace App\Endpoint\v1;

class PingEndpoint extends Endpoint {
    function process() {
        echo 'Processing..';
    }

    function requiresAuthenticated() {
        return true;
    }
}
?>