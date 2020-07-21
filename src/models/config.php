<?
namespace App\Models;

class Config {
    public static function get(string $path = ''){
        $parts = explode('/', $path);
        $config = self::getArray();
        
        foreach($parts as $bit){
            if(isset($config[$bit])){
                $config = $config[$bit];
            }
        }

        return $config;
    }
    public static function getArray(){
        return json_decode(file_get_contents(API_ROOT.'/config/config.json'), true) ?? array();
    }
}
?>